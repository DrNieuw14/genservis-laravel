<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Events\NewNotificationEvent;

class LeaveController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'reason' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from'
        ]);

        // ✅ SAVE LEAVE
        $leave = LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'reason' => $request->reason,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'status' => 'Pending'
        ]);

        // 🔔 NOTIFY SUPERVISORS
        $supervisors = \App\Models\User::where('role', 'supervisor')->get();

        foreach ($supervisors as $admin) {
            $notif = Notification::create([
                'user_id' => $admin->id,
                'type' => 'leave',
                'title' => 'New Leave Request',
                'message' => Auth::user()->name . ' submitted a leave request.',
                'is_read' => 0
            ]);

            event(new NewNotificationEvent($notif));
        }

        // 🔔 NOTIFY USER
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'leave',
            'title' => 'Leave Submitted',
            'message' => 'Your leave request has been submitted.',
            'is_read' => 0
        ]);

        return redirect('/leave/history')
            ->with('success', 'Leave submitted successfully!');
    }

    public function history()
    {
        $leaves = LeaveRequest::where('user_id', auth()->user()->id)
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
                $q->where('name', 'LIKE', '%' . $search . '%');
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
            'user_id' => $leave->user_id,
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
            'user_id' => $leave->user_id,
            'type' => 'leave',
            'title' => 'Leave Rejected',
            'message' => 'Your leave request has been rejected.',
            'is_read' => 0
        ]);

        event(new NewNotificationEvent($notif));

        return redirect()->back();
    }
    
}