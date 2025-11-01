<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Camp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

class UserManagementController extends Controller
{
    // Middleware is applied at the route level, so no need for constructor middleware

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'assignedCamps']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('name')->paginate(15);
        $roles = Role::orderBy('display_name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::orderBy('display_name')->get();
        $camps = Camp::active()->orderBy('display_name')->get();

        return view('admin.users.create', compact('roles', 'camps'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        // Check if a soft-deleted user exists with this email
        $softDeletedUser = User::onlyTrashed()->where('email', $request->email)->first();
        
        if ($softDeletedUser) {
            // If soft-deleted user exists, validate without unique constraint
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,id',
                'camp_assignments' => 'nullable|array',
                'camp_assignments.*' => 'exists:camps,id',
                'email_verified' => 'nullable|boolean',
            ]);
        } else {
            // Normal validation with unique constraint
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,id',
                'camp_assignments' => 'nullable|array',
                'camp_assignments.*' => 'exists:camps,id',
                'email_verified' => 'nullable|boolean',
            ]);
        }

        // Custom validation for camp role assignments
        if (!empty($validated['camp_assignments'])) {
            foreach ($validated['camp_assignments'] as $campId) {
                $roleId = $request->input("camp_role_{$campId}");
                if (empty($roleId)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "camp_role_{$campId}" => 'A role must be selected for each camp assignment.'
                    ]);
                }
                if (!Role::find($roleId)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "camp_role_{$campId}" => 'The selected role is invalid.'
                    ]);
                }
            }
        }

        if ($softDeletedUser) {
            // Restore and update the soft-deleted user
            $softDeletedUser->restore();
            $user = $softDeletedUser;
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => $request->has('email_verified') ? now() : null,
                'must_change_password' => false,
            ]);
        } else {
            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => $request->has('email_verified') ? now() : null,
            ]);
        }

        // Assign roles
        $user->syncRoles($validated['roles']);

        // Clear existing camp assignments before assigning new ones
        $user->assignedCamps()->detach();

        // Assign to camps if specified
        if (!empty($validated['camp_assignments'])) {
            foreach ($validated['camp_assignments'] as $campId) {
                $camp = Camp::find($campId);
                $roleId = $request->input("camp_role_{$campId}");
                
                if ($camp && $roleId) {
                    $isPrimary = $request->has("primary_camp_{$campId}");
                    $user->assignedCamps()->attach($camp->id, [
                        'role_id' => $roleId,
                        'is_primary' => $isPrimary,
                    ]);
                }
            }
        }

        // Send email verification notification if email is not verified
        if (!$request->has('email_verified')) {
            event(new Registered($user));
        }

        return redirect()->route('admin.users.index')
            ->with('success', $softDeletedUser ? 'User restored and updated successfully.' : 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'campAssignments.camp', 'campAssignments.role']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('display_name')->get();
        $camps = Camp::active()->orderBy('display_name')->get();
        $user->load(['roles', 'campAssignments.camp', 'campAssignments.role']);

        return view('admin.users.edit', compact('user', 'roles', 'camps'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'camp_assignments' => 'nullable|array',
            'camp_assignments.*' => 'exists:camps,id',
            'email_verified' => 'nullable|boolean',
        ]);

        // Custom validation for camp role assignments
        if (!empty($validated['camp_assignments'])) {
            foreach ($validated['camp_assignments'] as $campId) {
                $roleId = $request->input("camp_role_{$campId}");
                if (empty($roleId)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "camp_role_{$campId}" => 'A role must be selected for each camp assignment.'
                    ]);
                }
                if (!Role::find($roleId)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "camp_role_{$campId}" => 'The selected role is invalid.'
                    ]);
                }
            }
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Handle email verification
        if ($request->has('email_verified') && !$user->email_verified_at) {
            $updateData['email_verified_at'] = now();
            $updateData['must_change_password'] = true;
        } elseif (!$request->has('email_verified') && $user->email_verified_at) {
            $updateData['email_verified_at'] = null;
            $updateData['must_change_password'] = false;
        }

        $user->update($updateData);

        // Update roles
        $user->syncRoles($validated['roles']);

        // Update camp assignments
        $user->assignedCamps()->detach(); // Remove all existing assignments
        
        if (!empty($validated['camp_assignments'])) {
            foreach ($validated['camp_assignments'] as $campId) {
                $camp = Camp::find($campId);
                $roleId = $request->input("camp_role_{$campId}");
                
                if ($camp && $roleId) {
                    $isPrimary = $request->has("primary_camp_{$campId}");
                    $user->assignedCamps()->attach($camp->id, [
                        'role_id' => $roleId,
                        'is_primary' => $isPrimary,
                    ]);
                }
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Display a listing of soft deleted users.
     */
    public function trashed(Request $request)
    {
        $query = User::onlyTrashed()->with(['roles', 'assignedCamps']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('deleted_at', 'desc')->paginate(15);

        return view('admin.users.trashed', compact('users'));
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.trashed')
            ->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        
        // Prevent force deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.trashed')
                ->with('error', 'You cannot permanently delete your own account.');
        }

        $user->forceDelete();

        return redirect()->route('admin.users.trashed')
            ->with('success', 'User permanently deleted.');
    }

    /**
     * Resend email verification notification.
     */
    public function resendVerification(User $user)
    {
        // Only resend if email is not already verified
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Verification email sent to ' . $user->email . '.');
        }

        return redirect()->route('admin.users.index')
            ->with('info', 'User email is already verified.');
    }
} 