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
            'additionalRoles',
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
            'additionalRoles.permissions',
            'personnel.departmentRecord',
            'personnel.positionRecord',
            'personnel.employmentType',
        ]);

        $permissions = $user->allRoles()
            ->flatMap(fn ($role) => $role->permissions)
            ->where('status', true)
            ->unique('id')
            ->sortBy('name')
            ->groupBy('module');

        return view('admin.user-access.show', [

            'user' => $user,

            'permissions' => $permissions,

        ]);
    }

    public function edit(User $user)
    {
        $user->load(['systemRole', 'additionalRoles', 'personnel']);

        return view('admin.user-access.edit', [

            'user' => $user,

            'roles' => Role::where('status', true)->orderBy('name')->get(),

        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'additional_role_ids' => 'nullable|array',
            'additional_role_ids.*' => 'exists:roles,id',
        ]);

        $user->update([
            'role_id' => $validated['role_id'],
        ]);

        // The primary role can't also be listed as an additional role —
        // it's already covered above, and double-listing would just be
        // redundant pivot rows.
        $additionalRoleIds = collect($validated['additional_role_ids'] ?? [])
            ->reject(fn ($id) => (int) $id === (int) $validated['role_id'])
            ->values();

        $user->additionalRoles()->sync($additionalRoleIds);

        // TODO: Add Activity Log

        return redirect()
            ->route('admin.user-access.show', $user)
            ->with('success', 'Roles assigned successfully.');
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