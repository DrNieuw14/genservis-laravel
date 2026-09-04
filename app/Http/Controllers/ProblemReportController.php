<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\JobRequest;
use App\Models\Notification;
use App\Models\Personnel;
use App\Models\ProblemReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemReportController extends Controller
{
    // 📢 Quick report form — anyone who can submit a Job Request can also
    // report a problem (same "personnel"/"supervisor" audience).
    public function create()
    {
        return view('problem_reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:150',
            'problem_description' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:5120',
        ], [
            'location.required' => 'Please say where the problem is (e.g. Room 204, Main Building).',
            'problem_description.required' => 'Please describe the problem.',
            'photo.image' => 'The file must be a photo (JPG, PNG, etc.).',
            'photo.max' => 'The photo must be 5MB or smaller.',
        ]);

        $latestId = ProblemReport::max('id') + 1;
        $referenceNo = 'PR-' . date('Y') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('problem_reports', 'public');
        }

        $report = ProblemReport::create([
            'reference_no' => $referenceNo,
            'reported_by' => Auth::id(),
            'location' => $validated['location'],
            'problem_description' => $validated['problem_description'],
            'photo_path' => $photoPath,
            'status' => 'reported',
        ]);

        // 🔔 Notify whoever reviews problem reports (Physical Plant and
        // Services), same pattern as new Job Requests notifying approvers.
        $reviewers = User::withPermission('review-problem-reports')->get();

        foreach ($reviewers as $reviewer) {

            $notif = Notification::create([
                'user_id' => $reviewer->id,
                'type' => 'problem_report',
                'title' => 'New Problem Report',
                'url' => route('problem-reports.show', $report->id, false),
                'message' =>
                    (Auth::user()->fullname ?? Auth::user()->username)
                    . ' reported a problem at ' . $report->location . ': '
                    . $report->problem_description,
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }

        return redirect()
            ->route('problem-reports.my')
            ->with('success', 'Problem reported. Physical Plant and Services will review it.');
    }

    // ✏️ Reporter fixing their own report — only while Physical Plant and
    // Services hasn't acted on it yet, so an edit can't quietly invalidate
    // an assessment/conversion that already happened based on the original
    // details.
    public function edit($id)
    {
        $report = ProblemReport::findOrFail($id);

        if ($report->reported_by !== Auth::id() || $report->status !== 'reported') {
            abort(403);
        }

        return view('problem_reports.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $report = ProblemReport::findOrFail($id);

        if ($report->reported_by !== Auth::id() || $report->status !== 'reported') {
            abort(403);
        }

        $validated = $request->validate([
            'location' => 'required|string|max:150',
            'problem_description' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:5120',
        ], [
            'location.required' => 'Please say where the problem is (e.g. Room 204, Main Building).',
            'problem_description.required' => 'Please describe the problem.',
            'photo.image' => 'The file must be a photo (JPG, PNG, etc.).',
            'photo.max' => 'The photo must be 5MB or smaller.',
        ]);

        if ($request->hasFile('photo')) {
            if ($report->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($report->photo_path);
            }

            $validated['photo_path'] = $request->file('photo')->store('problem_reports', 'public');
        }

        unset($validated['photo']);

        $report->update($validated);

        return redirect()
            ->route('problem-reports.show', $report->id)
            ->with('success', 'Report updated.');
    }

    // 🗑️ Same "hasn't been touched yet" gate as edit — once Physical Plant
    // and Services has reviewed/converted/resolved/dismissed it, the report
    // is the record of what happened and shouldn't disappear.
    public function destroy($id)
    {
        $report = ProblemReport::findOrFail($id);

        if ($report->reported_by !== Auth::id() || $report->status !== 'reported') {
            abort(403);
        }

        if ($report->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($report->photo_path);
        }

        $report->delete();

        return redirect()
            ->route('problem-reports.my')
            ->with('success', 'Report deleted.');
    }

    // 📜 Reporter's own history
    public function my()
    {
        $reports = ProblemReport::where('reported_by', Auth::id())
            ->with('jobRequest')
            ->latest()
            ->paginate(15);

        return view('problem_reports.my', compact('reports'));
    }

    // 🔍 Review queue — Physical Plant and Services only
    public function index(Request $request)
    {
        if (!Auth::user()->hasPermission('review-problem-reports')) {
            abort(403);
        }

        $status = $request->query('status');

        $reports = ProblemReport::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->with('reporter')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('problem_reports.index', [
            'reports' => $reports,
            'status' => $status,
        ]);
    }

    public function show($id)
    {
        $report = ProblemReport::with(['reporter', 'reviewer', 'jobRequest'])->findOrFail($id);

        $canReview = Auth::user()->hasPermission('review-problem-reports');
        $isOwner = $report->reported_by === Auth::id();

        if (!$canReview && !$isOwner) {
            abort(403);
        }

        return view('problem_reports.show', compact('report', 'canReview', 'isOwner'));
    }

    // Save an assessment note without changing the status yet — lets
    // Physical Plant and Services record "needs budget for a new bulb"
    // before deciding whether/when to convert it into a Job Request.
    public function review(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('review-problem-reports')) {
            abort(403);
        }

        $report = ProblemReport::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:2000',
        ], [
            'admin_notes.required' => 'Please add an assessment note.',
        ]);

        $report->update([
            'admin_notes' => $validated['admin_notes'],
            'status' => $report->status === 'reported' ? 'reviewed' : $report->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Assessment saved.');
    }

    // 🛠️ The actual connection to Job Request — creates a real Job Request
    // from this report so it can be assigned and fixed, and links the two
    // records together.
    public function convertToJobRequest(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('review-problem-reports')) {
            abort(403);
        }

        $report = ProblemReport::findOrFail($id);

        if ($report->job_request_id) {
            return back()->with('error', 'This report has already been converted to a Job Request.');
        }

        $validated = $request->validate([
            'category' => 'required|in:physical_plant,utility',
            'office_unit_project' => 'required|string|max:150',
            'nature_of_request' => 'required|string|max:200',
            'work_summary' => 'required|string|max:2000',
        ], [
            'category.required' => 'Please select which team this should route to.',
        ]);

        $reporter = $report->reporter;
        $reporterPersonnel = Personnel::where('user_id', $report->reported_by)->first();

        $latestId = JobRequest::max('id') + 1;
        $referenceNo = 'JR-' . date('Y') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $jobRequest = JobRequest::create([
            'reference_no' => $referenceNo,
            'user_id' => $report->reported_by,
            'personnel_id' => $reporterPersonnel?->id,
            'requesting_party' => $reporterPersonnel?->fullname ?? ($reporter->fullname ?? $reporter->name),
            'department_id' => $reporterPersonnel?->department_id,
            'office_unit_project' => $validated['office_unit_project'],
            'category' => $validated['category'],
            'nature_of_request' => $validated['nature_of_request'],
            'work_summary' => $validated['work_summary'],
            'status' => 'pending',
        ]);

        $report->update([
            'job_request_id' => $jobRequest->id,
            'status' => 'converted',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $report->reported_by,
            'type' => 'problem_report',
            'title' => 'Problem Report Converted to Job Request',
            'url' => route('job-requests.show', $jobRequest->id, false),
            'message' => 'Your report "' . $report->location . '" is now Job Request ' . $referenceNo . '.',
            'is_read' => 0,
        ]);

        return redirect()
            ->route('problem-reports.show', $report->id)
            ->with('success', 'Converted to Job Request ' . $referenceNo . '.');
    }

    // Handled directly without needing a formal Job Request (e.g. a
    // custodian already screwed the bulb back in).
    public function resolve($id)
    {
        if (!Auth::user()->hasPermission('review-problem-reports')) {
            abort(403);
        }

        $report = ProblemReport::findOrFail($id);

        $report->update([
            'status' => 'resolved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Marked resolved.');
    }

    public function dismiss(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('review-problem-reports')) {
            abort(403);
        }

        $report = ProblemReport::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $report->update([
            'admin_notes' => $validated['admin_notes'] ?? $report->admin_notes,
            'status' => 'dismissed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report dismissed.');
    }
}
