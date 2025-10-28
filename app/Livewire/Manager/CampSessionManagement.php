<?php

namespace App\Livewire\Manager;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Camp;
use App\Models\CampInstance;
use Illuminate\Support\Facades\Auth;

class CampSessionManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $searchTerm = '';
    public $statusFilter = '';
    public $campFilter = '';

    // Session form properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $selectedCamp = null;
    public $selectedSession = null;

    // Session form data
    public $sessionName = '';
    public $sessionStartDate = '';
    public $sessionEndDate = '';
    public $sessionCapacity = '';
    public $sessionPrice = '';
    public $sessionIsActive = true;
    public $sessionDescription = '';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'campFilter' => ['except' => ''],
    ];

    public function mount()
    {
        // Set default camp filter to first assigned camp if available
        if ($this->assignedCamps->isNotEmpty() && empty($this->campFilter)) {
            $this->campFilter = $this->assignedCamps->first()->id;
        }
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedCampFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetSessionForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($sessionId)
    {
        $this->selectedSession = CampInstance::findOrFail($sessionId);
        
        // Check if user has access to this session's camp
        if (!Auth::user()->canAccessCampData($this->selectedSession->camp_id)) {
            session()->flash('error', 'You do not have permission to edit this session.');
            return;
        }
        
        $this->sessionName = $this->selectedSession->name;
        $this->sessionStartDate = $this->selectedSession->start_date->format('Y-m-d');
        $this->sessionEndDate = $this->selectedSession->end_date->format('Y-m-d');
        $this->sessionCapacity = $this->selectedSession->max_capacity;
        $this->sessionPrice = $this->selectedSession->price;
        $this->sessionDescription = $this->selectedSession->description;
        $this->sessionIsActive = $this->selectedSession->is_active;
        
        $this->showEditModal = true;
    }

    public function openDeleteModal($sessionId)
    {
        $this->selectedSession = CampInstance::findOrFail($sessionId);
        
        // Check if user has access to this session's camp
        if (!Auth::user()->canAccessCampData($this->selectedSession->camp_id)) {
            session()->flash('error', 'You do not have permission to delete this session.');
            return;
        }
        
        $this->showDeleteModal = true;
    }

    public function createSession()
    {
        $this->validate([
            'sessionName' => 'required|string|max:255',
            'sessionStartDate' => 'required|date|after:today',
            'sessionEndDate' => 'required|date|after:sessionStartDate',
            'sessionCapacity' => 'required|integer|min:1',
            'sessionPrice' => 'nullable|numeric|min:0',
            'sessionDescription' => 'nullable|string',
        ]);

        // Check if user has access to the selected camp
        if (!Auth::user()->canAccessCampData($this->campFilter)) {
            session()->flash('error', 'You do not have permission to create sessions for this camp.');
            return;
        }

        CampInstance::create([
            'camp_id' => $this->campFilter,
            'name' => $this->sessionName,
            'start_date' => $this->sessionStartDate,
            'end_date' => $this->sessionEndDate,
            'max_capacity' => $this->sessionCapacity,
            'price' => $this->sessionPrice ?: null,
            'description' => $this->sessionDescription,
            'is_active' => $this->sessionIsActive,
        ]);

        $this->showCreateModal = false;
        $this->resetSessionForm();
        session()->flash('message', 'Camp session created successfully.');
    }

    public function toggleSessionStatus($sessionId)
    {
        $session = CampInstance::findOrFail($sessionId);
        
        // Check if user has access to this session's camp
        if (!Auth::user()->canAccessCampData($session->camp_id)) {
            session()->flash('error', 'You do not have permission to modify this session.');
            return;
        }
        
        if ($session->is_active) {
            // Deactivating the session
            $session->deactivate();
            session()->flash('message', "Session deactivated successfully.");
        } else {
            // Activating the session (this will automatically deactivate other sessions for the same camp)
            $session->activate();
            session()->flash('message', "Session activated successfully. All other sessions for this camp have been deactivated.");
        }
    }

    public function updateSession()
    {
        $this->validate([
            'sessionName' => 'required|string|max:255',
            'sessionStartDate' => 'required|date',
            'sessionEndDate' => 'required|date|after:sessionStartDate',
            'sessionCapacity' => 'required|integer|min:1',
            'sessionPrice' => 'nullable|numeric|min:0',
            'sessionDescription' => 'nullable|string',
        ]);

        $this->selectedSession->update([
            'name' => $this->sessionName,
            'start_date' => $this->sessionStartDate,
            'end_date' => $this->sessionEndDate,
            'max_capacity' => $this->sessionCapacity,
            'price' => $this->sessionPrice ?: null,
            'description' => $this->sessionDescription,
            'is_active' => $this->sessionIsActive,
        ]);

        $this->showEditModal = false;
        $this->resetSessionForm();
        $this->selectedSession = null;
        session()->flash('message', 'Session updated successfully.');
    }

    public function deleteSession()
    {
        $sessionName = $this->selectedSession->name;
        $this->selectedSession->delete();
        
        $this->showDeleteModal = false;
        $this->selectedSession = null;
        session()->flash('message', "Session '{$sessionName}' deleted successfully.");
    }

    public function resetSessionForm()
    {
        $this->sessionName = '';
        $this->sessionStartDate = '';
        $this->sessionEndDate = '';
        $this->sessionCapacity = '';
        $this->sessionPrice = '';
        $this->sessionIsActive = true;
        $this->sessionDescription = '';
        $this->selectedSession = null;
    }

    public function getAssignedCampsProperty()
    {
        $user = Auth::user();
        
        if ($user->hasRole('system-admin')) {
            return Camp::orderBy('display_name')->get();
        }
        
        if ($user->hasRole('camp-manager')) {
            return $user->assignedCamps()->orderBy('display_name')->get();
        }
        
        return collect();
    }

    public function getSessionsProperty()
    {
        $query = CampInstance::with('camp');
        
        // Filter by camp if selected
        if (!empty($this->campFilter)) {
            $query->where('camp_id', $this->campFilter);
        } else {
            // If no camp filter, only show sessions for assigned camps
            $assignedCampIds = $this->assignedCamps->pluck('id');
            $query->whereIn('camp_id', $assignedCampIds);
        }

        // Search filter
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('camp', function ($campQuery) {
                      $campQuery->where('display_name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        // Status filter
        if (!empty($this->statusFilter)) {
            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query->orderBy('start_date', 'desc')->paginate(10);
    }



    public function render()
    {
        return view('livewire.manager.camp-session-management', [
            'camps' => $this->assignedCamps,
            'sessions' => $this->sessions,
        ]);
    }
}
