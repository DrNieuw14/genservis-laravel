<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Events\NewNotificationEvent;

class LeaveController extends Controller
{
    public function index()
    {
        $personnel = \App\Models\Personnel::where('user_id', Auth::id())->first();

        $leaves = LeaveRequest::where('personnel_id', $personnel->id)
            ->latest()
            ->get();

        return view('leave.form', compact('leaves'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from'
        ]);

        // ✅ ADD THIS HERE
        if (!Auth::user()->personnel_id) {
            return back()->with('error', 'User is not linked to personnel.');
        }

        // ✅ SAVE LEAVE
        $personnel = \App\Models\Personnel::where('user_id', Auth::id())->first();

        if (!$personnel) {
            return back()->with('error', 'User has no personnel record.');
        }

        $leave = LeaveRequest::create([
            'personnel_id' => $personnel->id,
            'reason' => $request->reason,
            'start_date' => $request->date_from,
            'end_date' => $request->date_to,
            'status' => 'Pending'
        ]);

               

        // 🔔 NOTIFY SUPERVISORS
        $supervisors = \App\Models\User::where('role', 'supervisor')->get();

        foreach ($supervisors as $admin) {
            $notif = Notification::create([
                'user_id' => $admin->id,
                'type' => 'leave',
                'title' => 'New Leave Request',
                'message' => Auth::user()->fullname . ' submitted a leave request.',
                'is_read' => 0
            ]);

            event(new NewNotificationEvent($notif));
        }

        // 🔔 NOTIFY USER
        Notification::create([
            'user_id' => auth()->user()->id,
            'type' => 'leave',
            'title' => 'Leave Submitted',
            'message' => 'Your leave request has been submitted.',
            'is_read' => 0
        ]);

        return redirect('/leave')
    ->with('success', 'Leave submitted successfully!');
    }

    public function history()
    {
        $personnel = \App\Models\Personnel::where('user_id', Auth::id())->first();

        $leaves = LeaveRequest::where('personnel_id', $personnel->id)
            ->latest()
            ->get();

        return view('leave.history', compact('leaves'));
    }

    public function adminIndex(Request $request)
    {
        if (auth()->user()->role !== 'supervisor') {
            abort(403);
        }

        $status = $request->status;
        $search = $request->search;

        $query = LeaveRequest::with(['user', 'approver']);

        // ✅ Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // ✅ Search by user name
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('fullname', 'LIKE', '%' . $search . '%');
            });
        }

        $leaves = $query->latest()->get();

        return view('leave.admin', compact('leaves', 'status', 'search'));
    }

    public function approve($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        // 🔔 Notify employee
        $notif = Notification::create([
            'user_id' => \App\Models\User::where('personnel_id', $leave->personnel_id)->value('id'),
            'type' => 'leave',
            'title' => 'Leave Approved',
            'message' => 'Your leave request has been approved.',
            'is_read' => 0
        ]);

        event(new NewNotificationEvent($notif));

        return redirect()->back();
    }

    public function reject($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => 'Rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        // 🔔 Notify employee
        $notif = Notification::create([
            'user_id' => \App\Models\User::where('personnel_id', $leave->personnel_id)->value('id'),
            'type' => 'leave',
            'title' => 'Leave Rejected',
            'message' => 'Your leave request has been rejected.',
            'is_read' => 0
        ]);

        event(new NewNotificationEvent($notif));

        return redirect()->back();
    }
    
}