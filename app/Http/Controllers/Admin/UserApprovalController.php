<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;

class UserApprovalController extends Controller
{
    public function index()
{
    $users = User::where('status', 'pending')
        ->where('role', 'personnel')
        ->latest()
        ->get();

    return view('admin.users.pending', [
        'users' => $users,
        'approvedCount' => User::where('status', 'approved')->count(),
        'rejectedCount' => User::where('status', 'rejected')->count(),
        'pendingCount'  => User::where('status', 'pending')->count(),
    ]);
}

    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'approved'
        ]);

        ActivityLogger::log(
            'Users',
            'Approved User',
            'Approved user: ' . ($user->fullname ?? $user->name)
        );

        return back()->with('success', "{$user->name} has been approved successfully.");
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'rejected'
        ]);

        ActivityLogger::log(
            'Users',
            'Rejected User',
            'Rejected user: ' . ($user->fullname ?? $user->name)
        );

        return back()->with('success', "{$user->name} has been rejected.");
    }
}