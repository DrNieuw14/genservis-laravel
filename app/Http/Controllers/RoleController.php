<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $roles = Role::withCount(['users', 'additionalUsers'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.index', compact('roles', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        // Submitted from the "Add New Role" modal (e.g. on the Assign Roles
        // page) vs. the standalone Create Role page — kept in separate error
        // bags so a validation failure doesn't collide with that page's own
        // role-assignment errors (role_id / additional_role_ids).
        $errorBag = $request->filled('redirect_to') ? 'createRole' : 'default';

        $validated = $request->validateWithBag($errorBag, [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $validated['name'] = trim($validated['name']);

        Role::create($validated);

        // TODO: Add Activity Log

        $redirectTo = $request->input('redirect_to');

        // Only ever follow a local path (e.g. back to Assign Roles) — never
        // an absolute/external URL, to avoid an open redirect via this field.
        $destination = (is_string($redirectTo) && str_starts_with($redirectTo, '/') && !str_starts_with($redirectTo, '//'))
            ? $redirectTo
            : route('roles.index');

        return redirect($destination)
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $validated['name'] = trim($validated['name']);

        $role->update($validated);

        // TODO: Activity Log

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function toggleStatus(Role $role)
    {
        if (in_array($role->name, [
            'Super Administrator',
            'System Administrator'
        ])) {

            return redirect()
                ->route('roles.index')
                ->with(
                    'error',
                    'This role is protected and cannot be deactivated.'
                );

        }

        $role->update([
            'status' => !$role->status
        ]);

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                $role->status
                    ? 'Role activated successfully.'
                    : 'Role deactivated successfully.'
            );
    }

    /**
     * We will not delete roles.
     */
    public function destroy(Role $role)
    {
        //
    }
}