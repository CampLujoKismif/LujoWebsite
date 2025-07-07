<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Camp;
use App\Models\CampInstance;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    // Middleware is applied at the route level, so no need for constructor middleware

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        try {
            // Get basic statistics
            $stats = [
                'total_users' => User::count(),
                'total_camps' => Camp::count(),
                'active_camps' => Camp::where('is_active', true)->count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
            ];

        // Get recent users
        $recentUsers = User::with(['roles', 'assignedCamps'])
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming camp instances
        $upcomingCamps = CampInstance::with('camp')
            ->where('start_date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Get role distribution
        $roleDistribution = Role::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(8)
            ->get();

        // Get camp capacity stats
        $campStats = Camp::select('display_name', 'max_capacity')
            ->whereNotNull('max_capacity')
            ->get()
            ->map(function ($camp) {
                $camp->current_capacity = $camp->getCurrentCapacityAttribute();
                $camp->capacity_percentage = $camp->max_capacity > 0 
                    ? round(($camp->current_capacity / $camp->max_capacity) * 100, 1)
                    : 0;
                return $camp;
            });

        // Get all camps for dashboard links
        $camps = Camp::with('assignedUsers')->orderBy('display_name')->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'upcomingCamps',
            'roleDistribution',
            'campStats',
            'camps'
        ));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Admin dashboard error: ' . $e->getMessage());
            
            // Return a simple error view or redirect
            return redirect()->route('dashboard')
                ->with('error', 'Unable to load admin dashboard. Please try again.');
        }
    }
} 