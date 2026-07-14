<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display the Role Permission Matrix.
     */
    public function index(Role $role)
    {
        $role->load([
            'permissions',
            'users',
        ]);

        $permissions = Permission::orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        $assignedPermissions = $role->permissions->count();

        $totalPermissions = Permission::count();

        $totalUsers = $role->users->count();

        return view(
            'admin.role-permissions.index',
            compact(
                'role',
                'permissions',
                'assignedPermissions',
                'totalPermissions',
                'totalUsers'
            )
        );
    }

    /**
     * Update Role Permissions.
     */
    public function update(Request $request, Role $role)
    {
        $role->permissions()->sync(
            $request->permissions ?? []
        );

        return redirect()
            ->route('roles.permissions', $role)
            ->with(
                'success',
                'Role permissions updated successfully.'
            );
    }
}