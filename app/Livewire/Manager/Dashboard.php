<?php

namespace App\Livewire\Manager;

use App\Models\Camp;
use App\Models\CampInstance;
use App\Models\Enrollment;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedCampId;
    public $selectedSessionId;
    public $selectedCamp;
    public $campInstances;
    public $selectedSession;
    public $stats = [];

    public function mount()
    {
        $user = auth()->user();
        
        // Get assigned camps
        $assignedCamps = $this->assignedCamps;
        
        if ($assignedCamps->isEmpty()) {
            return;
        }
        
        // Set default camp (primary or first)
        $primaryCamp = $assignedCamps->firstWhere('pivot.is_primary', true);
        $this->selectedCampId = $primaryCamp ? $primaryCamp->id : $assignedCamps->first()->id;
        $this->selectedCamp = Camp::find($this->selectedCampId);
        
        $this->loadCampSessions();
    }

    public function updatedSelectedCampId()
    {
        $this->selectedCamp = Camp::find($this->selectedCampId);
        $this->selectedSessionId = null;
        $this->selectedSession = null;
        $this->stats = [];
        $this->loadCampSessions();
    }

    public function updatedSelectedSessionId()
    {
        $this->selectedSession = $this->campInstances->find($this->selectedSessionId);
        $this->loadStats();
    }

    public function loadCampSessions()
    {
        if (!$this->selectedCamp) {
            $this->campInstances = collect();
            return;
        }

        // Get available sessions for the selected camp
        $this->campInstances = CampInstance::where('camp_id', $this->selectedCamp->id)
            ->with('camp')
            ->orderBy('year', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        // Set default to the most recent upcoming session
        $this->selectedSession = $this->campInstances
            ->where('start_date', '>=', now()->toDateString())
            ->first() ?? $this->campInstances->first();
            
        if ($this->selectedSession) {
            $this->selectedSessionId = $this->selectedSession->id;
            $this->loadStats();
        }
    }

    public function loadStats()
    {
        if (!$this->selectedSession) {
            return;
        }

        $enrollments = Enrollment::where('camp_instance_id', $this->selectedSession->id);
        
        $this->stats = [
            'total' => $enrollments->count(),
            'confirmed' => $enrollments->where('status', 'confirmed')->count(),
            'pending' => $enrollments->where('status', 'pending')->count(),
            'waitlisted' => $enrollments->where('status', 'waitlisted')->count(),
            'cancelled' => $enrollments->where('status', 'cancelled')->count(),
        ];
    }

    public function getAssignedCampsProperty()
    {
        $user = auth()->user();
        
        // For system-admins, check if they have camp assignments
        // If they do, show only their assigned camps; otherwise show all camps
        if ($user->hasRole('system-admin')) {
            $assignedCamps = $user->assignedCamps()->orderBy('display_name')->get();
            // If they have assignments, show only those; otherwise show all (for backward compatibility)
            return $assignedCamps->isNotEmpty() ? $assignedCamps : Camp::orderBy('display_name')->get();
        }
        
        if ($user->hasRole('camp-manager')) {
            return $user->assignedCamps()->orderBy('display_name')->get();
        }
        
        return collect();
    }

    public function getRecentEnrollmentsProperty()
    {
        if (!$this->selectedSession) {
            return collect();
        }

        return Enrollment::where('camp_instance_id', $this->selectedSession->id)
            ->with(['camper.family'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.manager.dashboard', [
            'assignedCamps' => $this->assignedCamps,
        ])->layout('components.layouts.app');
    }
}