<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserApprovalController extends Controller
{
    public function index()
    {
        $users = User::where('status', 'pending')->get();

        return view('admin.users.pending', compact('users'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return back()->with('success', 'User approved successfully.');
    }
}