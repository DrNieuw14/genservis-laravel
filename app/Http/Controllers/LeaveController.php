<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Events\NewNotificationEvent;
use App\Models\Personnel;

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
        'reason' => $request->reason,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => 'pending'
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
        'user_id' => Auth::id(),
        'type' => 'leave',
        'title' => 'Leave Submitted',
        'message' => 'Your leave request has been submitted.',
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
        if (auth()->user()->role !== 'supervisor') {
            abort(403);
        }

        $status = $request->status;
        $search = $request->search;

        $query = LeaveRequest::with('personnel.user');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('personnel.user', function ($q) use ($search) {
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

        $userId = $leave->personnel->user_id;

        Notification::create([
            'user_id' => $userId,
            'type' => 'leave',
            'title' => 'Leave Approved',
            'message' => 'Your leave request has been approved.',
            'is_read' => 0
        ]);

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

        $userId = $leave->personnel->user_id;

        Notification::create([
            'user_id' => $userId,
            'type' => 'leave',
            'title' => 'Leave Rejected',
            'message' => 'Your leave request has been rejected.',
            'is_read' => 0
        ]);

        return redirect()->back();
    }

    
    
}