<?php

namespace App\Livewire\Admin;

use App\Models\Camp;
use App\Models\CampInstance;
use App\Models\Camper;
use App\Models\Enrollment;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'total_camps' => Camp::count(),
            'total_camp_instances' => CampInstance::count(),
            'active_camp_instances' => CampInstance::where('is_active', true)->count(),
            'total_campers' => Camper::count(),
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::whereIn('status', ['pending', 'confirmed', 'waitlisted'])->count(),
            'confirmed_enrollments' => Enrollment::where('status', 'confirmed')->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
            'waitlisted_enrollments' => Enrollment::where('status', 'waitlisted')->count(),
            'cancelled_enrollments' => Enrollment::where('status', 'cancelled')->count(),
            'total_revenue' => Enrollment::sum('amount_paid_cents') / 100,
            'outstanding_balance' => Enrollment::selectRaw('SUM(GREATEST(0, balance_cents - amount_paid_cents)) as balance')->value('balance') / 100,
            
            // User role statistics
            'system_admins' => User::whereHas('roles', function($query) {
                $query->where('name', 'system-admin');
            })->count(),
            'camp_managers' => User::whereHas('roles', function($query) {
                $query->where('name', 'camp-manager');
            })->count(),
            'parents' => User::whereHas('roles', function($query) {
                $query->where('name', 'parent');
            })->count(),
            
            // Camp statistics
            'active_camps' => Camp::where('is_active', true)->count(),
            'total_sessions' => CampInstance::count(),
            'upcoming_sessions' => CampInstance::where('start_date', '>', now())->count(),
            
            // Analytics
            'enrollment_rate' => $this->calculateEnrollmentRate(),
            'revenue_growth' => $this->calculateRevenueGrowth(),
        ];
    }

    private function calculateEnrollmentRate()
    {
        $totalCapacity = CampInstance::sum('max_capacity') ?: 1;
        $totalEnrollments = Enrollment::whereIn('status', ['confirmed', 'pending'])->count();
        return ($totalEnrollments / $totalCapacity) * 100;
    }

    private function calculateRevenueGrowth()
    {
        // Simple calculation - can be enhanced with actual historical data
        $currentRevenue = Enrollment::where('created_at', '>=', now()->subMonth())->sum('amount_paid_cents') / 100;
        $previousRevenue = Enrollment::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->sum('amount_paid_cents') / 100;
        
        if ($previousRevenue == 0) {
            return $currentRevenue > 0 ? 100 : 0;
        }
        
        return (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
    }

    public function getRecentActivityProperty()
    {
        // Return recent activity - this can be enhanced with actual activity logging
        return collect([
            (object) [
                'description' => 'System initialized',
                'created_at' => now()->subMinutes(5)
            ]
        ]);
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('components.layouts.app');
    }
}
