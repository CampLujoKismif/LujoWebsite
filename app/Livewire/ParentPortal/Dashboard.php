<?php

namespace App\Livewire\ParentPortal;

use App\Models\CampInstance;
use App\Models\Enrollment;
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
        $user = auth()->user();
        
        // Get user's families and campers
        $families = $user->families()->with('campers')->get();
        $campers = collect();
        foreach ($families as $family) {
            $campers = $campers->concat($family->campers);
        }
        
        // Get active enrollments
        $activeEnrollments = Enrollment::whereIn('camper_id', $campers->pluck('id'))
            ->whereIn('status', ['pending', 'confirmed', 'waitlisted'])
            ->with(['camper', 'campInstance.camp'])
            ->get();
        
        // Calculate stats
        $this->stats = [
            'active_campers' => $campers->count(),
            'confirmed_enrollments' => $activeEnrollments->where('status', 'confirmed')->count(),
            'pending_forms' => $this->calculatePendingForms($activeEnrollments),
        ];
    }

    private function calculatePendingForms($enrollments)
    {
        $pendingCount = 0;
        foreach ($enrollments as $enrollment) {
            // Check if forms are required but not completed
            if (!$enrollment->forms_complete) {
                $pendingCount++;
            }
        }
        return $pendingCount;
    }

    public function getRecentActivityProperty()
    {
        $user = auth()->user();
        $families = $user->families()->with('campers')->get();
        $camperIds = collect();
        foreach ($families as $family) {
            $camperIds = $camperIds->concat($family->campers->pluck('id'));
        }

        // Get recent enrollments as activity
        $recentEnrollments = Enrollment::whereIn('camper_id', $camperIds)
            ->with(['camper', 'campInstance.camp'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $recentEnrollments->map(function ($enrollment) {
            return (object) [
                'description' => "Enrollment for {$enrollment->camper->full_name} in {$enrollment->campInstance->camp->display_name}",
                'created_at' => $enrollment->created_at
            ];
        });
    }

    public function getUpcomingSessionsProperty()
    {
        $user = auth()->user();
        $families = $user->families()->with('campers')->get();
        $camperIds = collect();
        foreach ($families as $family) {
            $camperIds = $camperIds->concat($family->campers->pluck('id'));
        }

        // Get all active sessions where user has confirmed enrollments
        $activeSessions = CampInstance::where('is_active', true)
            ->whereHas('enrollments', function ($query) use ($camperIds) {
                $query->whereIn('camper_id', $camperIds)
                      ->where('status', 'confirmed');
            })
            ->with('camp')
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        return $activeSessions;
    }

    public function render()
    {
        return view('livewire.parent-portal.dashboard')->layout('components.layouts.app');
    }
}