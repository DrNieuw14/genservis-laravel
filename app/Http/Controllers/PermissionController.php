<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        return view('permissions.index', [

            'permissions' => $permissions,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', [

            'permission' => $permission,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * Only description/status are editable here - slug/name/module stay
     * fixed because slugs are hardcoded throughout the app in
     * `permission:slug` route middleware and `hasPermission('slug')` checks,
     * so renaming one would silently break access control wherever it's used.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        if ($permission->slug === 'manage-permissions' && ! ($validated['status'] ?? false)) {

            return back()
                ->withInput()
                ->with('error', 'Cannot deactivate "Manage Permissions" - doing so would lock every administrator out of this page.');

        }

        $permission->update([
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? false,
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }
}
