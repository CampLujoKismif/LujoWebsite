<?php

namespace App\Livewire\ParentPortal;

use App\Models\Enrollment;
use App\Models\CampInstance;
use Livewire\Component;

class EnrollmentManagement extends Component
{
    public $enrollments;
    public $upcomingSessions;
    public $selectedEnrollment;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        
        // Get enrollments for the user's campers
        $this->enrollments = Enrollment::whereIn('camper_id', $user->accessibleCampers()->pluck('id'))
            ->with(['camper.family', 'campInstance.camp', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all active sessions that are open for registration
        $activeSessions = CampInstance::where('is_active', true)
            ->where('registration_open_date', '<=', now())
            ->where('registration_close_date', '>=', now())
            ->with('camp')
            ->orderBy('start_date')
            ->get();
        
        $this->upcomingSessions = $activeSessions;
    }

    public function render()
    {
        return view('livewire.parent-portal.enrollment-management')->layout('components.layouts.app');
    }
}
