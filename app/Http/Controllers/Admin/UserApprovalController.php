<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    public function index()
    {
        return view('admin.users.pending', [
            'pendingUsers'  => User::where('status', 'pending')
                                   ->where('role', 'personnel')
                                   ->latest()
                                   ->get(),
            'approvedCount' => User::where('status', 'approved')->count(),
            'rejectedCount' => User::where('status', 'rejected')->count(),
            'pendingCount'  => User::where('status', 'pending')->count(),
        ]);
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);
        return back()->with('success', "{$user->name} has been approved successfully.");
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);
        return back()->with('success', "{$user->name} has been rejected.");
    }
}