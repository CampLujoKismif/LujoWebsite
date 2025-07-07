<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampController extends Controller
{
    /**
     * Display a listing of camps the user has access to.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super_admin')) {
            // Super admins can see all camps
            $camps = Camp::active()->get();
        } else {
            // Other users can only see camps they're assigned to
            $camps = $user->assignedCamps()->where('is_active', true)->get();
        }

        return view('camps.index', compact('camps'));
    }

    /**
     * Display the specified camp.
     */
    public function show(Camp $camp)
    {
        $user = Auth::user();
        
        // Check if user has access to this camp
        if (!$user->canAccessCampData($camp->id)) {
            abort(403, 'You do not have access to this camp.');
        }

        // Get camp statistics based on user permissions
        $stats = $this->getCampStats($camp, $user);

        return view('camps.show', compact('camp', 'stats'));
    }

    /**
     * Show camp registrations (camp-specific access required).
     */
    public function registrations(Camp $camp)
    {
        $user = Auth::user();
        
        // Check if user has permission to view registrations for this camp
        if (!$user->hasPermissionInCamp('view_registrations', $camp->id)) {
            abort(403, 'You do not have permission to view registrations for this camp.');
        }

        $registrations = $camp->registrations()->with('camper')->get();

        return view('camps.registrations', compact('camp', 'registrations'));
    }

    /**
     * Show camp campers (camp-specific access required).
     */
    public function campers(Camp $camp)
    {
        $user = Auth::user();
        
        // Check if user has permission to view campers for this camp
        if (!$user->hasPermissionInCamp('view_campers', $camp->id)) {
            abort(403, 'You do not have permission to view campers for this camp.');
        }

        $campers = $camp->campers()->get();

        return view('camps.campers', compact('camp', 'campers'));
    }



    /**
     * Get camp statistics based on user permissions.
     */
    private function getCampStats(Camp $camp, User $user): array
    {
        $stats = [
            'total_campers' => 0,
            'total_registrations' => 0,
            'total_staff' => 0,
            'capacity_used' => 0,
            'capacity_total' => 0,
            'capacity_percentage' => 0,
        ];

        // Check permissions for each stat
        if ($user->hasPermissionInCamp('view_campers', $camp->id)) {
            $stats['total_campers'] = $camp->campers()->count();
        }

        if ($user->hasPermissionInCamp('view_registrations', $camp->id)) {
            $stats['total_registrations'] = $camp->registrations()->count();
        }

        if ($user->hasPermissionInCamp('view_staff', $camp->id)) {
            $stats['total_staff'] = $camp->staff()->count();
        }

        // Capacity info is generally available to camp staff
        if ($user->isAssignedToCamp($camp->id)) {
            $stats['capacity_used'] = 0;
            $stats['capacity_total'] = 0;
            $stats['capacity_percentage'] = 0;
        }

        return $stats;
    }

    /**
     * Assign a user to a camp (admin only).
     */
    public function assignUser(Request $request, Camp $camp)
    {
        $user = Auth::user();
        
        // Only super admins and camp directors can assign users
        if (!$user->hasAnyRole(['super_admin', 'camp_director'])) {
            abort(403, 'You do not have permission to assign users to camps.');
        }

        // Camp directors can only assign to their own camps
        if ($user->hasRole('camp_director') && !$user->isAssignedToCamp($camp->id)) {
            abort(403, 'You can only assign users to camps you are assigned to.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'position' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $targetUser = User::findOrFail($request->user_id);
        $role = \App\Models\Role::findOrFail($request->role_id);

        $targetUser->assignToCamp($camp, $role, [
            'position' => $request->position,
            'is_primary' => $request->is_primary ?? false,
        ]);

        return redirect()->back()->with('success', 'User assigned to camp successfully.');
    }

    /**
     * Remove a user from a camp (admin only).
     */
    public function removeUser(Request $request, Camp $camp)
    {
        $user = Auth::user();
        
        // Only super admins and camp directors can remove users
        if (!$user->hasAnyRole(['super_admin', 'camp_director'])) {
            abort(403, 'You do not have permission to remove users from camps.');
        }

        // Camp directors can only remove from their own camps
        if ($user->hasRole('camp_director') && !$user->isAssignedToCamp($camp->id)) {
            abort(403, 'You can only remove users from camps you are assigned to.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $targetUser = User::findOrFail($request->user_id);
        $targetUser->removeFromCamp($camp->id, $request->role_id);

        return redirect()->back()->with('success', 'User removed from camp successfully.');
    }

    /**
     * Show the dashboard for a specific camp.
     */
    public function dashboard(Camp $camp, Request $request)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        // Load camp with relationships
        $camp->load([
            'assignedUsers.roles',
            'assignedUsers' => function ($query) {
                $query->orderBy('name');
            }
        ]);

        // Get all instances for this camp
        $instances = $camp->instances()->orderByDesc('year')->get();

        // Get the selected instance (from query parameter or current instance)
        $selectedInstanceId = $request->query('instance');
        $currentInstance = null;
        
        if ($selectedInstanceId) {
            $currentInstance = $instances->find($selectedInstanceId);
        }
        
        if (!$currentInstance) {
            $currentInstance = $camp->currentInstance();
        }

        // Get staff statistics
        $staffStats = [
            'total' => $camp->assignedUsers->count(),
            'by_role' => $camp->assignedUsers->groupBy(function ($user) {
                return $user->roles->first() ? $user->roles->first()->display_name : 'No Role';
            })->map->count(),
        ];

        // Get primary staff (users with this as primary camp)
        $primaryStaff = $camp->assignedUsers->where('pivot.is_primary', true);

        return view('camps.dashboard', compact('camp', 'currentInstance', 'instances', 'staffStats', 'primaryStaff'));
    }

    /**
     * Show the staff roster for a specific camp.
     */
    public function staff(Camp $camp)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        $camp->load([
            'assignedUsers.roles',
            'assignedUsers' => function ($query) {
                $query->orderBy('name');
            }
        ]);

        return view('camps.staff', compact('camp'));
    }

    /**
     * Show the activities/schedule for a specific camp.
     */
    public function activities(Camp $camp)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        return view('camps.activities', compact('camp'));
    }

    /**
     * Show the settings for a specific camp.
     */
    public function settings(Camp $camp)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        // Get the current camp instance (most recent active instance)
        $currentInstance = $camp->currentInstance();

        return view('camps.settings', compact('camp', 'currentInstance'));
    }

    /**
     * Remove a staff member from a camp.
     */
    public function removeStaff(Camp $camp, User $user)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        // Remove the user from the camp
        $camp->assignedUsers()->detach($user->id);

        return redirect()->route('camps.staff', $camp)
            ->with('success', 'Staff member removed from camp successfully.');
    }
} 