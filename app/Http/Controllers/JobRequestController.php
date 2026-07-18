<?php

namespace App\Http\Controllers;

use App\Models\JobRequest;
use App\Models\JobRequestPhoto;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewNotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobRequestController extends Controller
{
    // 📄 Show form
    public function create()
    {
        $departments = Department::orderBy('department_name')->get();

        return view('job_requests.create', compact('departments'));
    }

    // 💾 Store request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'office_unit_project' => 'required|string|max:150',
            'category' => 'required|in:physical_plant,utility',
            'nature_of_request' => 'required|string|max:200',
            'work_summary' => 'required|string|max:2000',
            'work_category' => 'nullable|string|max:150',
            'target_date' => 'nullable|date',
        ], [
            'office_unit_project.required' => 'Please enter the office, unit, or project location.',
            'category.required' => 'Please select what kind of job request this is.',
            'nature_of_request.required' => 'Please give a short title for this request.',
            'work_summary.required' => 'Please describe the work needed.',
        ]);

        $personnel = Personnel::where('user_id', Auth::id())->first();

        $latestId = JobRequest::max('id') + 1;

        $referenceNo = 'JR-'
            . date('Y')
            . '-'
            . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        $jobRequest = JobRequest::create([
            'reference_no' => $referenceNo,
            'user_id' => Auth::id(),
            'personnel_id' => $personnel?->id,
            'requesting_party' => $personnel?->fullname ?? (Auth::user()->fullname ?? Auth::user()->name),
            'department_id' => $validated['department_id'] ?? $personnel?->department_id,
            'office_unit_project' => $validated['office_unit_project'],
            'category' => $validated['category'],
            'nature_of_request' => $validated['nature_of_request'],
            'work_summary' => $validated['work_summary'],
            'work_category' => $validated['work_category'] ?? null,
            'target_date' => $validated['target_date'] ?? null,
            'status' => 'pending',
        ]);

        // 🔔 Notify whoever approves this category of job request
        $approvers = User::withPermission($jobRequest->approvalPermission())->get();

        foreach ($approvers as $approver) {

            $notif = Notification::create([
                'user_id' => $approver->id,
                'type' => 'job_request',
                'title' => 'New Job Request',
                'url' => route('job-requests.show', $jobRequest->id, false),
                'message' =>
                    (Auth::user()->fullname ?? Auth::user()->username)
                    . ' submitted a job request: '
                    . $jobRequest->nature_of_request,
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }

        return redirect()
            ->route('job-requests.history')
            ->with('success', 'Job request submitted successfully!');
    }

    // 📜 Requester's own submissions
    public function history()
    {
        $requests = JobRequest::with(['department', 'assignedPersonnel'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('job_requests.history', compact('requests'));
    }

    // 🔧 Jobs assigned to the current account as work crew
    public function myAssignedJobs()
    {
        $personnel = Auth::user()->personnel;

        $requests = $personnel
            ? JobRequest::with(['department', 'assignedPersonnel'])
                ->whereHas('assignedPersonnel', fn ($q) => $q->where('personnel.id', $personnel->id))
                ->latest()
                ->get()
            : collect();

        return view('job_requests.my-assigned', compact('requests'));
    }

    // 📊 Processing queue — filtered to whichever category(ies) the viewer can approve
    public function index()
    {
        $user = Auth::user();

        if (!$user->hasPermission('approve-job-requests-physical-plant') && !$user->hasPermission('approve-job-requests-utility')) {
            abort(403);
        }

        $categories = [];

        if ($user->hasPermission('approve-job-requests-physical-plant')) {
            $categories[] = 'physical_plant';
        }

        if ($user->hasPermission('approve-job-requests-utility')) {
            $categories[] = 'utility';
        }

        $requests = JobRequest::with(['user', 'department', 'assignedPersonnel', 'photos'])
            ->whereIn('category', $categories)
            ->latest()
            ->paginate(15);

        $pendingCount = (clone $requests->getCollection())->where('status', 'pending')->count();

        return view('job_requests.index', compact('requests', 'pendingCount'));
    }

    public function show($id)
    {
        $jobRequest = JobRequest::with([
            'user', 'personnel', 'department', 'approver', 'assigner', 'workDoneBy', 'assignedPersonnel', 'photos.uploader'
        ])->findOrFail($id);

        $user = Auth::user();

        $canApprove = $user->hasPermission($jobRequest->approvalPermission());
        $canAssign = $user->hasPermission('assign-job-request-personnel');
        $isOwner = $jobRequest->user_id === $user->id;
        $isAssignedWorker = $jobRequest->isAssignedTo($user);

        if (!$canApprove && !$canAssign && !$isOwner && !$isAssignedWorker && !$user->hasPermission('view-job-requests')) {
            abort(403);
        }

        return view('job_requests.show', compact('jobRequest', 'canApprove', 'canAssign', 'isOwner', 'isAssignedWorker'));
    }

    public function approve(Request $request, $id)
    {
        $jobRequest = JobRequest::findOrFail($id);

        if (!Auth::user()->hasPermission($jobRequest->approvalPermission())) {
            abort(403);
        }

        $validated = $request->validate([
            'remarks' => 'nullable|string|max:2000',
        ]);

        $jobRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'remarks' => $validated['remarks'] ?? $jobRequest->remarks,
        ]);

        Notification::create([
            'user_id' => $jobRequest->user_id,
            'type' => 'job_request',
            'title' => 'Job Request Approved',
            'url' => route('job-requests.show', $jobRequest->id, false),
            'message' => 'Your job request "' . $jobRequest->nature_of_request . '" was approved.',
            'is_read' => 0,
        ]);

        return back()->with('success', 'Job request approved.');
    }

    public function reject(Request $request, $id)
    {
        $jobRequest = JobRequest::findOrFail($id);

        if (!Auth::user()->hasPermission($jobRequest->approvalPermission())) {
            abort(403);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ], [
            'rejection_reason.required' => 'Please explain why this request is being rejected.',
        ]);

        $jobRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        Notification::create([
            'user_id' => $jobRequest->user_id,
            'type' => 'job_request',
            'title' => 'Job Request Rejected',
            'url' => route('job-requests.show', $jobRequest->id, false),
            'message' => 'Your job request "' . $jobRequest->nature_of_request . '" was rejected.',
            'is_read' => 0,
        ]);

        return back()->with('success', 'Job request rejected.');
    }

    // 👷 Personnel assignment — a separate step from approval
    public function assignForm($id)
    {
        if (!Auth::user()->hasPermission('assign-job-request-personnel')) {
            abort(403);
        }

        $jobRequest = JobRequest::with('assignedPersonnel')->findOrFail($id);

        if ($jobRequest->status !== 'approved' && $jobRequest->status !== 'assigned') {
            return back()->with('error', 'This request must be approved before personnel can be assigned.');
        }

        // Same utility/maintenance staff pool used for both categories —
        // rehabilitation jobs are carried out by the same crew as utility
        // help requests (see Personnel::scopeUtilityStaff()).
        $staff = Personnel::with(['departmentRecord', 'positionRecord'])
            ->utilityStaff()
            ->orderBy('fullname')
            ->get();

        return view('job_requests.assign', compact('jobRequest', 'staff'));
    }

    public function assignStore(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('assign-job-request-personnel')) {
            abort(403);
        }

        $jobRequest = JobRequest::findOrFail($id);

        $validated = $request->validate([
            'personnel_ids' => 'required|array|min:1',
            'personnel_ids.*' => 'exists:personnel,id',
        ], [
            'personnel_ids.required' => 'Please select at least one person to assign.',
        ]);

        $syncResult = $jobRequest->assignedPersonnel()->sync($validated['personnel_ids']);

        $jobRequest->update([
            'status' => 'assigned',
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
        ]);

        Notification::create([
            'user_id' => $jobRequest->user_id,
            'type' => 'job_request',
            'title' => 'Job Request Personnel Assigned',
            'url' => route('job-requests.show', $jobRequest->id, false),
            'message' => 'Personnel have been assigned to your job request "' . $jobRequest->nature_of_request . '".',
            'is_read' => 0,
        ]);

        // 🔔 Notify only the newly-assigned workers (e.g. Rony, Aldrin) —
        // not everyone already assigned from a prior edit, so re-saving
        // the same crew doesn't spam them again.
        $newlyAssignedPersonnelIds = $syncResult['attached'] ?? [];

        if (!empty($newlyAssignedPersonnelIds)) {

            $newlyAssignedUsers = User::whereHas(
                'personnel',
                fn ($q) => $q->whereIn('personnel.id', $newlyAssignedPersonnelIds)
            )->get();

            foreach ($newlyAssignedUsers as $workerUser) {

                $notif = Notification::create([
                    'user_id' => $workerUser->id,
                    'type' => 'job_request',
                    'title' => 'You Were Assigned a Job Request',
                    'url' => route('job-requests.show', $jobRequest->id, false),
                    'message' =>
                        (Auth::user()->fullname ?? Auth::user()->username)
                        . ' assigned you to "' . $jobRequest->nature_of_request
                        . '" at ' . $jobRequest->office_unit_project . '.',
                    'is_read' => 0,
                ]);

                event(new NewNotificationEvent($notif));
            }
        }

        return redirect()
            ->route('job-requests.show', $jobRequest->id)
            ->with('success', 'Personnel assigned successfully.');
    }

    // 🔧 An assigned worker (e.g. Rony or Aldrin) flags the job's work as
    // finished — optionally attaching photo evidence — and notifies
    // whoever assigned/approved the job (e.g. Mark) to do the final
    // close-out. With a multi-person crew, the FIRST assigned worker to
    // submit moves the status to work_done; anyone still assigned can
    // keep adding their own evidence photos afterward, right up until
    // Mark closes the job out with Mark Completed.
    public function markWorkDone(Request $request, $id)
    {
        $jobRequest = JobRequest::findOrFail($id);

        $user = Auth::user();

        if (!$jobRequest->isAssignedTo($user)) {
            abort(403);
        }

        if (!in_array($jobRequest->status, ['assigned', 'work_done'])) {
            return back()->with('error', 'This job isn\'t in a state where work can be marked done.');
        }

        $validated = $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|max:5120',
        ], [
            'photos.*.image' => 'Each file must be a photo (JPG, PNG, etc.).',
            'photos.*.max' => 'Each photo must be 5MB or smaller.',
        ]);

        $isFirstConfirmation = $jobRequest->status === 'assigned';

        if ($isFirstConfirmation) {
            $jobRequest->update([
                'status' => 'work_done',
                'work_done_by' => $user->id,
                'work_done_at' => now(),
            ]);
        }

        $uploadedCount = 0;

        foreach ($request->file('photos', []) as $photo) {

            if (!$photo) {
                continue;
            }

            $path = $photo->store('job_requests', 'public');

            JobRequestPhoto::create([
                'job_request_id' => $jobRequest->id,
                'path' => $path,
                'uploaded_by' => $user->id,
            ]);

            $uploadedCount++;
        }

        // Notify whoever assigned the crew and whoever approved the
        // request (often the same person).
        $notifyUserIds = collect([$jobRequest->assigned_by, $jobRequest->approved_by])
            ->filter()
            ->unique();

        if ($isFirstConfirmation) {

            foreach ($notifyUserIds as $notifyUserId) {

                $notif = Notification::create([
                    'user_id' => $notifyUserId,
                    'type' => 'job_request',
                    'title' => 'Job Request Work Done',
                    'url' => route('job-requests.show', $jobRequest->id, false),
                    'message' =>
                        ($user->fullname ?? $user->username)
                        . ' finished the work for "' . $jobRequest->nature_of_request
                        . '". Please review and mark it completed.',
                    'is_read' => 0,
                ]);

                event(new NewNotificationEvent($notif));
            }

            return back()->with('success', 'Marked as done. The approver has been notified to close out this request.');
        }

        // Already work_done — this is a second (or later) assigned worker
        // adding their own evidence, not a status change.
        if ($uploadedCount > 0) {

            foreach ($notifyUserIds as $notifyUserId) {

                $notif = Notification::create([
                    'user_id' => $notifyUserId,
                    'type' => 'job_request',
                    'title' => 'More Job Request Evidence Added',
                    'url' => route('job-requests.show', $jobRequest->id, false),
                    'message' =>
                        ($user->fullname ?? $user->username)
                        . ' added ' . $uploadedCount . ' more photo(s) to "' . $jobRequest->nature_of_request . '".',
                    'is_read' => 0,
                ]);

                event(new NewNotificationEvent($notif));
            }

            return back()->with('success', 'Photos added.');
        }

        return back()->with('success', 'This job was already marked done — attach a photo if you have one to add.');
    }

    public function markCompleted($id)
    {
        $jobRequest = JobRequest::findOrFail($id);

        if (!Auth::user()->hasPermission('assign-job-request-personnel')) {
            abort(403);
        }

        if ($jobRequest->status !== 'work_done') {
            return back()->with('error', 'Wait for the assigned personnel to mark their work done before closing this out.');
        }

        $jobRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Job request marked as completed.');
    }

    public function print($id)
    {
        $jobRequest = JobRequest::with([
            'user', 'personnel', 'department', 'approver', 'assignedPersonnel'
        ])->findOrFail($id);

        return view('job_requests.print', compact('jobRequest'));
    }

    // 📊 Completed Job Requests report — for Mark/PPS to compile their own
    // records of finished work, with the evidence photos attached.
    public function report(Request $request)
    {
        return view('job_requests.report', $this->reportData($request));
    }

    public function reportPrint(Request $request)
    {
        return view('job_requests.report_print', $this->reportData($request));
    }

    private function reportData(Request $request): array
    {
        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');
        $category = $request->input('category');

        $completedJobs = JobRequest::with(['department', 'approver', 'assignedPersonnel', 'photos'])
            ->where('status', 'completed')
            ->when($dateFrom, fn ($q) => $q->whereDate('completed_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('completed_at', '<=', $dateTo))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderByDesc('completed_at')
            ->get();

        $totalCompleted = $completedJobs->count();
        $physicalPlantCount = $completedJobs->where('category', 'physical_plant')->count();
        $utilityCount = $completedJobs->where('category', 'utility')->count();
        $totalPhotos = $completedJobs->sum(fn ($job) => $job->photos->count());

        return [
            'completedJobs' => $completedJobs,
            'totalCompleted' => $totalCompleted,
            'physicalPlantCount' => $physicalPlantCount,
            'utilityCount' => $utilityCount,
            'totalPhotos' => $totalPhotos,
            'dateFrom' => $dateFrom?->format('Y-m-d'),
            'dateTo' => $dateTo?->format('Y-m-d'),
            'category' => $category,
        ];
    }
}
