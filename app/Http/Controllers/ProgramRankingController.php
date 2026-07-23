<?php

namespace App\Http\Controllers;

use App\Imports\ProgramRankingImport;
use App\Models\AdmissionYear;
use App\Models\ProgramQuota;
use App\Models\ProgramRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ProgramRankingController extends Controller
{
    public function index(Request $request)
    {
        $years = AdmissionYear::orderByDesc('id')->get();

        $selectedYearId = $request->input('admission_year_id', $years->first()?->id);
        $selectedYear = $years->firstWhere('id', (int) $selectedYearId);

        $programCounts = collect();
        $totalCount = 0;

        if ($selectedYear) {
            $quotas = ProgramQuota::where('admission_year_id', $selectedYear->id)
                ->get()
                ->keyBy('program_code');

            $programCounts = ProgramRanking::where('admission_year_id', $selectedYear->id)
                ->get()
                ->groupBy(fn ($r) => $r->shortProgramCode())
                ->map(function ($group, $code) use ($quotas) {
                    $quota = $quotas->get($code);

                    return [
                        'code' => $code,
                        'program' => $group->first()->program,
                        'count' => $group->count(),
                        'quota' => $quota && $quota->isSet() ? $quota->quota() : null,
                    ];
                })
                ->sortByDesc('count')
                ->values();

            $totalCount = $programCounts->sum('count');
        }

        return view('program_rankings.index', compact('years', 'selectedYear', 'programCounts', 'totalCount'));
    }

    public function importForm()
    {
        $years = AdmissionYear::orderByDesc('id')->get();

        return view('program_rankings.import', compact('years'));
    }

    public function importStore(Request $request)
    {
        $validated = $request->validate([
            'admission_year_id' => 'required|exists:admission_years,id',
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // One consolidated master list per admission year — re-uploading
        // (e.g. an updated Raw Data export) replaces the whole set rather
        // than appending, same reasoning as ExamSession's re-upload.
        ProgramRanking::where('admission_year_id', $validated['admission_year_id'])->delete();

        $import = new ProgramRankingImport((int) $validated['admission_year_id']);

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('program-rankings.index', ['admission_year_id' => $validated['admission_year_id']])
            ->with('success', "{$import->imported} examinee(s) imported — {$import->matched} matched, {$import->nameMismatch} name mismatch(es), {$import->notFound} not found in the roster.");
    }

    public function showProgram(Request $request, $yearId, $programCode)
    {
        $year = AdmissionYear::findOrFail($yearId);

        $rankings = ProgramRanking::where('admission_year_id', $year->id)
            ->get()
            ->filter(fn ($r) => $r->shortProgramCode() === $programCode)
            ->sortByDesc('grade')
            ->values();

        $programName = $rankings->first()?->program ?? $programCode;

        $quota = ProgramQuota::firstOrNew([
            'admission_year_id' => $year->id,
            'program_code' => $programCode,
        ]);

        $rankings = $this->applyAdmissionStatus($rankings, $quota);

        return view('program_rankings.show', compact('year', 'rankings', 'programCode', 'programName', 'quota'));
    }

    public function updateQuota(Request $request, $yearId, $programCode)
    {
        $year = AdmissionYear::findOrFail($yearId);

        $validated = $request->validate([
            'sections' => 'required|integer|min:0',
            'students_per_section' => 'required|integer|min:0',
            'program_name' => 'nullable|string|max:255',
        ]);

        ProgramQuota::updateOrCreate(
            ['admission_year_id' => $year->id, 'program_code' => $programCode],
            [
                'program_name' => $validated['program_name'] ?? null,
                'sections' => $validated['sections'],
                'students_per_section' => $validated['students_per_section'],
                'updated_by' => Auth::id(),
            ]
        );

        return redirect()
            ->route('program-rankings.show', [$year->id, $programCode])
            ->with('success', 'Program capacity saved.');
    }

    /**
     * Marks each ranking 'admitted' true/false/null (null = no quota set
     * yet). Ties at the cutoff score are ALL admitted, per the agreed
     * design — the Nth-ranked person's Grade becomes the cutoff, and
     * anyone at or above it gets in, even if that means the actual
     * admitted count runs slightly over the nominal quota.
     */
    private function applyAdmissionStatus(Collection $rankings, ProgramQuota $quota): Collection
    {
        $quotaValue = $quota->isSet() ? $quota->quota() : 0;
        $total = $rankings->count();

        // $rankings must already be sorted by grade descending and
        // re-indexed (values()) — the item at position quotaValue-1 is
        // the Nth-ranked person, whose Grade becomes the cutoff.
        $cutoffGrade = ($quotaValue > 0 && $quotaValue < $total)
            ? $rankings->get($quotaValue - 1)?->grade
            : null;

        return $rankings->map(function ($r) use ($quotaValue, $total, $cutoffGrade) {
            $r->admitted = match (true) {
                $quotaValue <= 0 => null,
                $quotaValue >= $total => true,
                $r->grade === null => false,
                default => $r->grade >= $cutoffGrade,
            };

            return $r;
        });
    }

    // Every exam taker across every program for the year, in one list —
    // unlike showProgram() this is paginated/searchable since it can run
    // into the thousands (3,093 on the real 2026-2027 upload), too many to
    // reasonably render on one page like a single program's list.
    public function showAll(Request $request, $yearId)
    {
        $year = AdmissionYear::findOrFail($yearId);

        $search = $request->input('search');

        $rankings = ProgramRanking::where('admission_year_id', $year->id)
            ->when($search, fn ($q) => $q->where('examinee_name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%"))
            ->orderByDesc('grade')
            ->paginate(50)
            ->withQueryString();

        $cutoffs = $this->cutoffGradesByProgram($year->id);

        foreach ($rankings as $r) {
            $cutoff = $cutoffs[$r->shortProgramCode()] ?? null;

            $r->admitted = match (true) {
                $cutoff === null => null,
                $cutoff['admit_all'] => true,
                $r->grade === null => false,
                default => $r->grade >= $cutoff['grade'],
            };
        }

        return view('program_rankings.all', compact('year', 'rankings', 'search'));
    }

    // The actual admitted list, grouped by program — only programs with a
    // capacity set are included, since a program with none has no
    // admit/not-admit line drawn yet (nothing to report). An optional
    // ?program= code (e.g. from a single program's own ranking page)
    // narrows this down to just that one program's report.
    public function admittedReport(Request $request, $yearId)
    {
        $year = AdmissionYear::findOrFail($yearId);

        $groups = $this->admittedByProgram($year->id, $request->query('program'));

        return view('program_rankings.admitted_report', compact('year', 'groups'));
    }

    public function admittedReportPrint(Request $request, $yearId)
    {
        $year = AdmissionYear::findOrFail($yearId);

        $groups = $this->admittedByProgram($year->id, $request->query('program'));

        return view('program_rankings.admitted_report_print', compact('year', 'groups'));
    }

    private function admittedByProgram(int $yearId, ?string $onlyProgramCode = null): Collection
    {
        $quotas = ProgramQuota::where('admission_year_id', $yearId)
            ->when($onlyProgramCode, fn ($q) => $q->where('program_code', $onlyProgramCode))
            ->get();

        $groups = collect();

        foreach ($quotas as $quota) {
            if (!$quota->isSet()) {
                continue;
            }

            $rankings = ProgramRanking::where('admission_year_id', $yearId)
                ->get()
                ->filter(fn ($r) => $r->shortProgramCode() === $quota->program_code)
                ->sortByDesc('grade')
                ->values();

            $rankings = $this->applyAdmissionStatus($rankings, $quota);

            $admitted = $rankings->filter(fn ($r) => $r->admitted === true)->values();

            if ($admitted->isEmpty()) {
                continue;
            }

            $groups->push([
                'code' => $quota->program_code,
                'program' => $admitted->first()->program ?? $quota->program_name,
                'quota' => $quota->quota(),
                'admitted' => $admitted,
            ]);
        }

        return $groups->sortBy('code')->values();
    }

    /**
     * One targeted pass per program that actually has a quota set (a
     * handful of DB round-trips at most — this app's whole rankings table
     * tops out in the low thousands, not a performance concern), so a
     * paginated slice of "All Exam Takers" can still know each row's
     * admitted status without re-deriving it from a full per-program sort
     * on every request.
     */
    private function cutoffGradesByProgram(int $yearId): array
    {
        $quotas = ProgramQuota::where('admission_year_id', $yearId)->get();

        $cutoffs = [];

        foreach ($quotas as $quota) {
            if (!$quota->isSet()) {
                continue;
            }

            $programRows = ProgramRanking::where('admission_year_id', $yearId)
                ->get()
                ->filter(fn ($r) => $r->shortProgramCode() === $quota->program_code)
                ->sortByDesc('grade')
                ->values();

            $total = $programRows->count();
            $quotaValue = $quota->quota();

            $cutoffs[$quota->program_code] = $quotaValue >= $total
                ? ['admit_all' => true, 'grade' => null]
                : ['admit_all' => false, 'grade' => $programRows->get($quotaValue - 1)?->grade];
        }

        return $cutoffs;
    }
}
