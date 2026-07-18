<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\ActivityLog;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            ->orderByDesc('updated_at')
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

    /**
     * Reset Password screen — lets an Administrator or Inventory Custodian
     * generate a new temporary password for anyone who forgot theirs,
     * without needing the broader view-user-access permission.
     */
    public function resetPasswordIndex()
    {
        $users = User::with('personnel')
            ->get(['id', 'name', 'username', 'role'])
            ->reject(fn ($user) => $user->role === 'supervisor' && auth()->user()->role !== 'supervisor')
            ->sortBy(fn ($user) => optional($user->personnel)->fullname ?? $user->name)
            ->values();

        return view('admin.user-access.reset-password', compact('users'));
    }

    public function resetPassword(User $user)
    {
        if ($user->role === 'supervisor' && auth()->user()->role !== 'supervisor') {
            abort(403, 'The super admin account can only reset its own password.');
        }

        $temporaryPassword = Str::random(10);

        $user->update([
            'password' => Hash::make($temporaryPassword),
            'must_change_password' => true,
        ]);

        ActivityLogger::log(
            'Users',
            'Reset User Password',
            'Reset password for ' . (optional($user->personnel)->fullname ?? $user->name) . ' (' . $user->username . ').',
            $user->id
        );

        return response()->json([
            'fullname' => optional($user->personnel)->fullname ?? $user->name,
            'username' => $user->username,
            'temporary_password' => $temporaryPassword,
        ]);
    }

    /**
     * Password-reset audit trail — deliberately gated to the super admin
     * account only (see routes/web.php), not the Administrator permission,
     * so every admin-level password reset stays visible to one account
     * nobody else can revoke or reassign.
     */
    public function resetPasswordLogs()
    {
        $logs = ActivityLog::with(['user', 'targetUser'])
            ->where('module', 'Users')
            ->where('action', 'Reset User Password')
            ->latest()
            ->paginate(25);

        return view('admin.user-access.reset-password-logs', compact('logs'));
    }
}