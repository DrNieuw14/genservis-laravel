<?php

namespace App\Http\Controllers;

use App\Imports\ExamResultImport;
use App\Models\AdmissionYear;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExamSessionController extends Controller
{
    public function index()
    {
        $sessions = ExamSession::with('year')
            ->withCount('results')
            ->latest('exam_date')
            ->get();

        $years = AdmissionYear::orderByDesc('id')->get();

        return view('exam_sessions.index', compact('sessions', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_year_id' => 'required|exists:admission_years,id',
            'label' => 'required|string|max:100',
            'exam_date' => 'nullable|date',
        ], [
            'label.required' => 'Please enter a label for this exam session (e.g. April 21, 2026).',
        ]);

        $session = ExamSession::create($validated + [
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('exam-sessions.show', $session->id)
            ->with('success', 'Exam session created. You can now upload its results.');
    }

    public function show($id)
    {
        $session = ExamSession::with(['year', 'results.applicant'])->findOrFail($id);

        $summary = [
            'total' => $session->results->count(),
            'matched' => $session->results->where('match_status', \App\Models\ExamResult::MATCHED)->count(),
            'name_mismatch' => $session->results->where('match_status', \App\Models\ExamResult::NAME_MISMATCH)->count(),
            'not_found' => $session->results->where('match_status', \App\Models\ExamResult::NOT_FOUND)->count(),
        ];

        return view('exam_sessions.show', compact('session', 'summary'));
    }

    public function importForm($id)
    {
        $session = ExamSession::with('year')->findOrFail($id);

        return view('exam_sessions.import', compact('session'));
    }

    public function importStore(Request $request, $id)
    {
        $session = ExamSession::findOrFail($id);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Re-uploading into the same session (e.g. a corrected results file)
        // replaces its results rather than appending duplicates alongside
        // the old ones.
        $session->results()->delete();

        $import = new ExamResultImport($session->id, $session->admission_year_id);

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('exam-sessions.show', $session->id)
            ->with('success', "{$import->imported} result(s) imported — {$import->matched} matched, {$import->nameMismatch} name mismatch(es), {$import->notFound} not found in the roster.");
    }

    public function destroy($id)
    {
        $session = ExamSession::findOrFail($id);
        $session->delete();

        return redirect()
            ->route('exam-sessions.index')
            ->with('success', 'Exam session and its results deleted.');
    }
}
