<?php

namespace App\Livewire\Manager;

use App\Models\CampInstance;
use App\Models\Enrollment;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedSessionId;
    public $campInstances;
    public $selectedSession;
    public $stats = [];

    public function mount()
    {
        $user = auth()->user();
        
        // Get available sessions for the manager's camps
        $this->campInstances = CampInstance::whereHas('camp', function ($query) use ($user) {
            $query->whereIn('id', $user->assignedCamps()->pluck('camps.id'));
        })
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

    public function updatedSelectedSessionId()
    {
        $this->selectedSession = $this->campInstances->find($this->selectedSessionId);
        $this->loadStats();
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
        return view('livewire.manager.dashboard')->layout('components.layouts.app');
    }
}