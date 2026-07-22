<?php

namespace App\Http\Controllers;

use App\Imports\ProgramRankingImport;
use App\Models\AdmissionYear;
use App\Models\ProgramRanking;
use Illuminate\Http\Request;
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
            $programCounts = ProgramRanking::where('admission_year_id', $selectedYear->id)
                ->get()
                ->groupBy(fn ($r) => $r->shortProgramCode())
                ->map(fn ($group, $code) => [
                    'code' => $code,
                    'program' => $group->first()->program,
                    'count' => $group->count(),
                ])
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

        return view('program_rankings.show', compact('year', 'rankings', 'programCode', 'programName'));
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

        return view('program_rankings.all', compact('year', 'rankings', 'search'));
    }
}
