<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function index()
    {
        $query = User::with([
            'systemRole',
            'personnel.departmentRecord',
            'personnel.positionRecord',
            'personnel.employmentType',
        ]);

        if (request('search')) {

            $search = request('search');

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhereHas('personnel', function ($personnel) use ($search) {

                        $personnel->where('employee_id', 'like', "%{$search}%")
                                ->orWhere('fullname', 'like', "%{$search}%");

                });

            });

        }

        $users = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.user-access.index', [

            'users' => $users,

            'totalUsers' => User::count(),

            'activeUsers' => User::where('status', 'approved')->count(),

            'pendingUsers' => User::where('status', 'pending')->count(),

            'roles' => Role::orderBy('name')->get(),

        ]);
    }

    public function show(User $user)
    {
        $user->load([
            'systemRole.permissions',
            'personnel.departmentRecord',
            'personnel.positionRecord',
            'personnel.employmentType',
        ]);

        $permissions = $user->systemRole
            ? $user->systemRole->permissions
                ->where('status', true)
                ->sortBy('name')
                ->groupBy('module')
            : collect();

        return view('admin.user-access.show', [

            'user' => $user,

            'permissions' => $permissions,

        ]);
    }

    public function edit(User $user)
    {
        $user->load(['systemRole', 'personnel']);

        return view('admin.user-access.edit', [

            'user' => $user,

            'roles' => Role::where('status', true)->orderBy('name')->get(),

        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'role_id' => $validated['role_id'],
        ]);

        // TODO: Add Activity Log

        return redirect()
            ->route('admin.user-access.show', $user)
            ->with('success', 'Role assigned successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,suspended,locked,inactive',
        ]);

        if ($validated['status'] === 'approved' && !$user->personnel) {
            return redirect()
                ->route('admin.user-access.show', $user)
                ->with('error', 'This user must complete onboarding before the account can be activated.');
        }

        $user->update([
            'status' => $validated['status'],
        ]);

        // TODO: Add Activity Log

        return redirect()
            ->route('admin.user-access.show', $user)
            ->with('success', 'Account status updated to ' . ucfirst($validated['status']) . '.');
    }
}