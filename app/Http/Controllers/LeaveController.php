<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Events\NewNotificationEvent;
use App\Models\Personnel;
use App\Helpers\ActivityLogger;

class LeaveController extends Controller
{
    public function index()
    {
        $personnel = Personnel::where('user_id', Auth::id())->first();

        // ✅ SAFETY CHECK (ADD THIS)
        if (!$personnel) {
            return redirect()->back()->with('error', 'Personnel record not found.');
        }

        $leaves = LeaveRequest::where('personnel_id', $personnel->id)
            ->latest()
            ->get();

        return view('leave.form', compact('leaves'));
    }

    public function store(Request $request)
{
    $request->validate([
        'leave_type' => 'required|in:' . implode(',', \App\Models\LeaveRequest::LEAVE_TYPES),
        'reason' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date'
    ]);

    $personnel = Personnel::where('user_id', Auth::id())->first();

    // ✅ ADD THIS
    if (!$personnel) {
        return redirect()->back()->with('error', 'Personnel record not found.');
    }

    $leave = LeaveRequest::create([
        'personnel_id' => $personnel->id,
        'leave_type' => $request->leave_type,
        'reason' => $request->reason,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => 'Pending'
    ]);

    ActivityLogger::log(
        'Leave',
        'Submitted Leave',
        'Submitted leave request from ' . ($personnel->fullname ?? 'Unknown Personnel')
    );

    // 🔔 NOTIFY LEAVE APPROVERS (HR Officer / Administrator) — previously
    // only ever reached the legacy role='supervisor' account, which meant
    // whoever actually holds approve-leave-requests (e.g. HR) never heard
    // about a new submission. Same fix already applied to the utility-leave
    // branch below.
    $approvers = \App\Models\User::withPermission('approve-leave-requests')->get();

    foreach ($approvers as $admin) {
        $notif = Notification::create([
            'user_id' => $admin->id,
            'type' => 'leave',
            'title' => 'New Leave Request',
            'message' => Auth::user()->name . ' submitted a leave request.',
            'url' => route('leave.requests', [], false),
            'is_read' => 0
        ]);

        event(new NewNotificationEvent($notif));
    }

    // 🔔 NOTIFY MARK (or whoever else holds approve-utility-leave) when the
    // applicant is Utility & Maintenance Staff — the block above only ever
    // reaches the legacy role='supervisor' account, which Mark isn't, so
    // without this he'd never hear about a utility leave request at all.
    if (Personnel::utilityStaff()->where('id', $personnel->id)->exists()) {

        $approvers = \App\Models\User::withPermission('approve-utility-leave')->get();

        foreach ($approvers as $approver) {

            $notif = Notification::create([
                'user_id' => $approver->id,
                'type' => 'leave',
                'title' => 'New Utility Leave Request',
                'message' => ($personnel->fullname ?? Auth::user()->name) . ' applied for ' . $leave->leave_type . '.',
                'url' => route('utility-leave.index', [], false),
                'is_read' => 0
            ]);

            event(new NewNotificationEvent($notif));
        }
    }

    // 🔔 NOTIFY USER
    Notification::create([
        'user_id' => Auth::id(),
        'type' => 'leave',
        'title' => 'Leave Submitted',
        'message' => 'Your leave request has been submitted.',
        'url' => route('leave.history', [], false),
        'is_read' => 0
    ]);

    return redirect('/leave')->with('success', 'Leave submitted successfully!');
}

    

    public function history()
    {
        $personnel = Personnel::where('user_id', Auth::id())->first();

        // ✅ SAFETY CHECK
        if (!$personnel) {
            return redirect()->back()->with('error', 'Personnel record not found.');
        }

        $leaves = LeaveRequest::where('personnel_id', $personnel->id)
            ->latest()
            ->get();

        return view('leave.history', compact('leaves'));
    }

    public function adminIndex(Request $request)
    {
        if (!auth()->user()->hasPermission('approve-leave-requests')) {
            abort(403);
        }

        $status = $request->status;
        $search = $request->search;

        $query = LeaveRequest::with('personnel.user');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('personnel', function ($q) use ($search) {
                $q->where('fullname', 'LIKE', '%' . $search . '%');
            });
        }

        $leaves = $query->latest()->get();

        return view('leave.admin', compact('leaves', 'status', 'search'));
    }

    public function approve($id)
    {
        if (!auth()->user()->hasPermission('approve-leave-requests')) {
            abort(403);
        }

        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => 'Approved'
        ]);

        ActivityLogger::log(
            'Leave',
            'Approved Leave',
            'Approved leave request of ' . ($leave->personnel->fullname ?? 'Unknown Personnel')
        );

        $userId = $leave->personnel->user_id;

        Notification::create([
            'user_id' => $userId,
            'type' => 'leave',
            'title' => 'Leave Approved',
            'message' => 'Your leave request has been approved.',
            'url' => route('leave.history', [], false),
            'is_read' => 0
        ]);

        return redirect()->back();
    }

    public function reject($id)
    {
        if (!auth()->user()->hasPermission('approve-leave-requests')) {
            abort(403);
        }

        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => 'Rejected'
        ]);

        ActivityLogger::log(
            'Leave',
            'Rejected Leave',
            'Rejected leave request of ' . ($leave->personnel->fullname ?? 'Unknown Personnel')
        );

        $userId = $leave->personnel->user_id;

        Notification::create([
            'user_id' => $userId,
            'type' => 'leave',
            'title' => 'Leave Rejected',
            'message' => 'Your leave request has been rejected.',
            'url' => route('leave.history', [], false),
            'is_read' => 0
        ]);

        return redirect()->back();
    }

    
    
}