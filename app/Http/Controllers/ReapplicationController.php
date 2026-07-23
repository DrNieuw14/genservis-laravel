<?php

namespace App\Http\Controllers;

use App\Imports\ReapplicationImport;
use App\Models\AdmissionYear;
use App\Models\ProgramQuota;
use App\Models\ProgramRanking;
use App\Models\Reapplication;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ReapplicationController extends Controller
{
    public function index(Request $request)
    {
        $years = AdmissionYear::orderByDesc('id')->get();

        $selectedYearId = $request->input('admission_year_id', $years->first()?->id);
        $selectedYear = $years->firstWhere('id', (int) $selectedYearId);

        $search = $request->input('search');
        $filter = $request->input('filter');
        $programCode = $request->input('program');

        $reapplications = collect();
        $programQuotaStatus = null;

        // Defaults: alphabetical when browsing everyone, but Score/Grade
        // defaults to highest-first and Rank defaults to best-first (rank 1)
        // the moment either is chosen — either by an explicit click on that
        // column header, or implicitly by filtering to one program (where
        // "who scored highest" is the whole point of that view).
        $defaultDirections = ['surname' => 'asc', 'grade' => 'desc', 'rank' => 'asc'];
        $sort = $request->input('sort') ?: ($programCode ? 'grade' : 'surname');
        $direction = $request->input('direction') ?: $defaultDirections[$sort] ?? 'asc';

        if ($selectedYear) {
            $choiceText = $programCode ? Reapplication::choiceTextForProgramCode($programCode) : null;

            $query = Reapplication::where('admission_year_id', $selectedYear->id)
                ->when($search, fn ($q) => $q->where('surname', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('control_number', 'like', "%{$search}%"))
                ->when($filter === 'duplicates', fn ($q) => $q->where('is_duplicate', true))
                ->when($filter === 'unresolved', fn ($q) => $q->where('control_number', null))
                ->when($choiceText, fn ($q) => $q->where(fn ($sub) => $sub
                    ->whereRaw('UPPER(first_choice) = ?', [$choiceText])
                    ->orWhereRaw('UPPER(second_choice) = ?', [$choiceText])));

            if (in_array($sort, ['grade', 'rank'], true)) {
                // Score/Grade and Rank only exist after cross-referencing
                // ProgramRanking in PHP (attachExamInfo()) — can't be sorted
                // with an ->orderBy() at the query level, so this pulls
                // every match (a few hundred at most, same scale already
                // proven fine elsewhere in this app), sorts in PHP, then
                // slices the requested page manually. Rows with no exam
                // record always sort to the bottom regardless of direction
                // — "no data" isn't a real ranking position in either
                // direction.
                $all = $query->get();
                $this->attachExamInfo($all);

                $valueOf = fn ($r) => $sort === 'grade' ? $r->examGrade : $r->examRank;

                [$withValue, $withoutValue] = $all->partition(fn ($r) => $valueOf($r) !== null);

                $all = $withValue->sortBy($valueOf, SORT_REGULAR, $direction === 'desc')
                    ->concat($withoutValue)
                    ->values();

                $page = (int) $request->input('page', 1);
                $perPage = 50;

                $reapplications = new LengthAwarePaginator(
                    $all->forPage($page, $perPage)->values(),
                    $all->count(),
                    $perPage,
                    $page,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
            } else {
                $reapplications = $query->orderBy('surname')
                    ->paginate(50)
                    ->withQueryString();

                $this->attachExamInfo($reapplications);
            }

            if ($choiceText) {
                $programQuotaStatus = $this->quotaStatusForProgram($selectedYear->id, $programCode);
            }
        }

        $duplicateCount = $selectedYear
            ? Reapplication::where('admission_year_id', $selectedYear->id)->where('is_duplicate', true)->count()
            : 0;

        $unresolvedCount = $selectedYear
            ? Reapplication::where('admission_year_id', $selectedYear->id)->whereNull('control_number')->count()
            : 0;

        $programOptions = Reapplication::CHOICE_TO_PROGRAM_CODE;

        return view('reapplications.index', compact(
            'years', 'selectedYear', 'reapplications', 'search', 'filter', 'duplicateCount', 'unresolvedCount',
            'programCode', 'programOptions', 'programQuotaStatus', 'sort', 'direction'
        ));
    }

    // Same tie-inclusive admit logic as ProgramRankingController — kept
    // as its own small copy here rather than a cross-controller call,
    // since this only needs the final admitted count, not the full
    // per-row breakdown the rankings page renders.
    private function quotaStatusForProgram(int $yearId, string $programCode): ?array
    {
        $quota = ProgramQuota::where('admission_year_id', $yearId)
            ->where('program_code', $programCode)
            ->first();

        if (!$quota || !$quota->isSet()) {
            return null;
        }

        $rankings = ProgramRanking::where('admission_year_id', $yearId)
            ->get()
            ->filter(fn ($r) => $r->shortProgramCode() === $programCode)
            ->sortByDesc('grade')
            ->values();

        $total = $rankings->count();
        $quotaValue = $quota->quota();

        if ($quotaValue >= $total) {
            $admitted = $total;
        } else {
            $cutoffGrade = $rankings->get($quotaValue - 1)?->grade;
            $admitted = $rankings->filter(fn ($r) => $r->grade !== null && $r->grade >= $cutoffGrade)->count();
        }

        return [
            'quota' => $quotaValue,
            'admitted' => $admitted,
            'remaining' => max(0, $quotaValue - $admitted),
        ];
    }

    public function importForm()
    {
        $years = AdmissionYear::orderByDesc('id')->get();

        return view('reapplications.import', compact('years'));
    }

    public function importStore(Request $request)
    {
        $validated = $request->validate([
            'admission_year_id' => 'required|exists:admission_years,id',
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $yearId = (int) $validated['admission_year_id'];

        // One consolidated submission list per admission year — re-uploading
        // replaces the whole set, same reasoning as every other import in
        // this module.
        Reapplication::where('admission_year_id', $yearId)->delete();

        $import = new ReapplicationImport($yearId);

        Excel::import($import, $request->file('file'));

        $duplicateCount = $this->flagDuplicates($yearId);

        return redirect()
            ->route('reapplications.index', ['admission_year_id' => $yearId])
            ->with('success', "{$import->imported} reapplication(s) imported — {$import->matched} matched, {$import->nameMismatch} name mismatch(es), {$import->notFound} not found in the roster, {$duplicateCount} flagged as possible duplicate submissions.");
    }

    public function show($id)
    {
        $reapplication = Reapplication::with(['year', 'applicant'])->findOrFail($id);

        $this->attachExamInfo([$reapplication]);

        return view('reapplications.show', compact('reapplication'));
    }

    /**
     * For every matched reapplication, pulls their actual exam score/grade
     * and their rank within their original program's ranked list — this is
     * exactly the context Admission Testing staff need to judge a
     * reapplication (how close did they actually come?), even though the
     * app doesn't auto-decide placement. Caches each program's full sorted
     * list once per request rather than once per row, since many rows on
     * one page typically share the same original program (BSHM especially).
     */
    private function attachExamInfo(iterable $reapplications): void
    {
        // NOT collect($reapplications)->pluck(...) — when $reapplications is
        // a LengthAwarePaginator, collect() sees it implements Arrayable and
        // unwraps its pagination METADATA (current_page, data, total, ...),
        // not the actual rows, silently producing an empty id list. A plain
        // foreach works correctly for a paginator, a plain array, or a
        // Collection alike.
        $applicantIds = [];

        foreach ($reapplications as $r) {
            if ($r->admission_applicant_id) {
                $applicantIds[] = $r->admission_applicant_id;
            }
        }

        $applicantIds = array_unique($applicantIds);

        if (empty($applicantIds)) {
            foreach ($reapplications as $r) {
                $r->examScore = null;
                $r->examGrade = null;
                $r->examRank = null;
                $r->examTotal = null;
                $r->examProgramCode = null;
            }

            return;
        }

        $ownRankings = ProgramRanking::whereIn('admission_applicant_id', $applicantIds)
            ->get()
            ->keyBy('admission_applicant_id');

        $sortedByProgram = [];

        foreach ($reapplications as $r) {
            $own = $r->admission_applicant_id ? $ownRankings->get($r->admission_applicant_id) : null;

            if (!$own) {
                $r->examScore = null;
                $r->examGrade = null;
                $r->examRank = null;
                $r->examTotal = null;
                $r->examProgramCode = null;

                continue;
            }

            $programCode = $own->shortProgramCode();
            $cacheKey = $own->admission_year_id . ':' . $programCode;

            if (!isset($sortedByProgram[$cacheKey])) {
                $sortedByProgram[$cacheKey] = ProgramRanking::where('admission_year_id', $own->admission_year_id)
                    ->get()
                    ->filter(fn ($pr) => $pr->shortProgramCode() === $programCode)
                    ->sortByDesc('grade')
                    ->values();
            }

            $sorted = $sortedByProgram[$cacheKey];

            $r->examScore = $own->raw_score;
            $r->examGrade = $own->grade;
            $r->examRank = $sorted->search(fn ($pr) => $pr->id === $own->id) + 1;
            $r->examTotal = $sorted->count();
            $r->examProgramCode = $programCode;
        }
    }

    // Marks every row whose Control Number is shared by more than one row
    // in this same upload — genuinely the same person submitting more than
    // once, not just a coincidence, since Control Number is specific to
    // one real applicant. "N/A"/blank control numbers are never grouped
    // this way (normalizeControlNumber() already turned them into null).
    private function flagDuplicates(int $yearId): int
    {
        Reapplication::where('admission_year_id', $yearId)->update(['is_duplicate' => false]);

        $duplicateControlNumbers = Reapplication::where('admission_year_id', $yearId)
            ->whereNotNull('control_number')
            ->selectRaw('control_number, count(*) as c')
            ->groupBy('control_number')
            ->having('c', '>', 1)
            ->pluck('control_number');

        if ($duplicateControlNumbers->isEmpty()) {
            return 0;
        }

        return Reapplication::where('admission_year_id', $yearId)
            ->whereIn('control_number', $duplicateControlNumbers)
            ->update(['is_duplicate' => true]);
    }
}
