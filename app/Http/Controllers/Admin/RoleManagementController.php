<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleManagementController extends Controller
{
    // Middleware is applied at the route level, so no need for constructor middleware

    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('display_name')->paginate(15);
        
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_admin' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $validated['is_admin'] = $request->has('is_admin');

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
            'is_admin' => $validated['is_admin'],
        ]);

        // Assign permissions
        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->permissions()->attach($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load(['users', 'permissions']);
        
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $role->load('permissions');
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_admin' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $validated['is_admin'] = $request->has('is_admin');

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
            'is_admin' => $validated['is_admin'],
        ]);

        // Update permissions
        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting system roles
        $systemRoles = ['super_admin', 'camp_director', 'board_member', 'camp_staff', 'youth_minister', 'church_representative', 'parent', 'camper'];
        
        if (in_array($role->name, $systemRoles)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete system roles.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role with assigned users.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Display a listing of soft deleted roles.
     */
    public function trashed()
    {
        $roles = Role::onlyTrashed()->withCount(['users', 'permissions'])->orderBy('deleted_at', 'desc')->paginate(15);
        
        return view('admin.roles.trashed', compact('roles'));
    }

    /**
     * Restore a soft deleted role.
     */
    public function restore($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        return redirect()->route('admin.roles.trashed')
            ->with('success', 'Role restored successfully.');
    }

    /**
     * Permanently delete a role.
     */
    public function forceDelete($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        
        // Prevent deleting system roles
        $systemRoles = ['super_admin', 'camp_director', 'board_member', 'camp_staff', 'youth_minister', 'church_representative', 'parent', 'camper'];
        
        if (in_array($role->name, $systemRoles)) {
            return redirect()->route('admin.roles.trashed')
                ->with('error', 'Cannot permanently delete system roles.');
        }

        // Check if role has users
        if ($role->users()->withTrashed()->count() > 0) {
            return redirect()->route('admin.roles.trashed')
                ->with('error', 'Cannot permanently delete role with assigned users.');
        }

        $role->forceDelete();

        return redirect()->route('admin.roles.trashed')
            ->with('success', 'Role permanently deleted.');
    }
} 