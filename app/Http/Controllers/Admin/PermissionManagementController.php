<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    // Middleware is applied at the route level, so no need for constructor middleware

    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        $query = Permission::withCount('roles');

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $permissions = $query->orderBy('group')->orderBy('display_name')->paginate(20);
        $groups = Permission::distinct()->pluck('group')->sort();

        return view('admin.permissions.index', compact('permissions', 'groups'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $groups = Permission::distinct()->pluck('group')->sort();
        
        return view('admin.permissions.create', compact('groups'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255',
        ]);

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        $permission->load(['roles.users']);
        
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        $groups = Permission::distinct()->pluck('group')->sort();
        
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255',
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Display a listing of soft deleted permissions.
     */
    public function trashed(Request $request)
    {
        $query = Permission::onlyTrashed()->withCount('roles');

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $permissions = $query->orderBy('deleted_at', 'desc')->paginate(20);
        $groups = Permission::onlyTrashed()->distinct()->pluck('group')->sort();

        return view('admin.permissions.trashed', compact('permissions', 'groups'));
    }

    /**
     * Restore a soft deleted permission.
     */
    public function restore($id)
    {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->restore();

        return redirect()->route('admin.permissions.trashed')
            ->with('success', 'Permission restored successfully.');
    }

    /**
     * Permanently delete a permission.
     */
    public function forceDelete($id)
    {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        
        // Check if permission is assigned to any roles
        if ($permission->roles()->withTrashed()->count() > 0) {
            return redirect()->route('admin.permissions.trashed')
                ->with('error', 'Cannot permanently delete permission that is assigned to roles.');
        }

        $permission->forceDelete();

        return redirect()->route('admin.permissions.trashed')
            ->with('success', 'Permission permanently deleted.');
    }
} 