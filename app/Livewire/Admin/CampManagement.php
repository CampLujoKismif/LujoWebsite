<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Camp;
use App\Models\CampInstance;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class CampManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $searchTerm = '';
    public $statusFilter = '';

    // Camp form properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showSessionModal = false;
    public $showEditSessionModal = false;
    public $showDeleteSessionModal = false;
    public $selectedCamp = null;
    public $selectedSession = null;

    // Camp form data
    public $name = '';
    public $displayName = '';
    public $description = '';
    public $isActive = true;
    public $price = '';

    // Session form data
    public $sessionName = '';
    public $sessionStartDate = '';
    public $sessionEndDate = '';
    public $sessionCapacity = '';
    public $sessionPrice = '';
    public $sessionIsActive = true;
    public $sessionDescription = '';
    public $registrationOpenDate = '';
    public $registrationCloseDate = '';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Data is loaded in the getCampsProperty method
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetCampForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($campId)
    {
        $this->selectedCamp = Camp::findOrFail($campId);
        
        $this->name = $this->selectedCamp->name;
        $this->displayName = $this->selectedCamp->display_name;
        $this->description = $this->selectedCamp->description;
        $this->isActive = $this->selectedCamp->is_active;
        $this->price = $this->selectedCamp->price;
        
        $this->showEditModal = true;
    }

    public function openDeleteModal($campId)
    {
        $this->selectedCamp = Camp::with('instances')->findOrFail($campId);
        $this->showDeleteModal = true;
    }

    public function openSessionModal($campId = null)
    {
        $this->selectedCamp = $campId ? Camp::findOrFail($campId) : null;
        $this->resetSessionForm();
        $this->showSessionModal = true;
    }

    public function createCamp()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:camps',
            'displayName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        Camp::create([
            'name' => $this->name,
            'display_name' => $this->displayName,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'price' => $this->price ?: null,
        ]);

        $this->showCreateModal = false;
        $this->resetCampForm();
        session()->flash('message', 'Camp created successfully.');
    }

    public function updateCamp()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:camps,name,' . $this->selectedCamp->id,
            'displayName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        $this->selectedCamp->update([
            'name' => $this->name,
            'display_name' => $this->displayName,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'price' => $this->price ?: null,
        ]);

        $this->showEditModal = false;
        $this->resetCampForm();
        session()->flash('message', 'Camp updated successfully.');
    }

    public function deleteCamp()
    {
        $campName = $this->selectedCamp->display_name;
        
        // Delete all associated sessions (camp instances)
        // Using DB transaction to ensure data integrity
        DB::transaction(function () {
            // Get all instances associated with this camp
            $instances = $this->selectedCamp->instances()->get();
            
            // Delete all instances (soft delete since both models use SoftDeletes)
            foreach ($instances as $instance) {
                $instance->delete();
            }
            
            // Delete the camp (soft delete)
            $this->selectedCamp->delete();
        });
        
        $this->showDeleteModal = false;
        $this->selectedCamp = null;
        session()->flash('message', "Camp '{$campName}' and all associated sessions have been deleted successfully.");
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
            'registrationOpenDate' => 'nullable|date',
            'registrationCloseDate' => 'nullable|date|after_or_equal:registrationOpenDate',
        ]);

        $camp = $this->selectedCamp ?: Camp::findOrFail(request('camp_id'));

        CampInstance::create([
            'camp_id' => $camp->id,
            'name' => $this->sessionName,
            'start_date' => $this->sessionStartDate,
            'end_date' => $this->sessionEndDate,
            'max_capacity' => $this->sessionCapacity,
            'price' => $this->sessionPrice ?: null,
            'description' => $this->sessionDescription,
            'is_active' => $this->sessionIsActive,
            'registration_open_date' => $this->registrationOpenDate ?: null,
            'registration_close_date' => $this->registrationCloseDate ?: null,
        ]);

        $this->showSessionModal = false;
        $this->resetSessionForm();
        session()->flash('message', 'Camp session created successfully.');
    }

    public function toggleSessionStatus($sessionId)
    {
        $session = CampInstance::findOrFail($sessionId);
        
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

    public function openEditSessionModal($sessionId)
    {
        $this->selectedSession = CampInstance::findOrFail($sessionId);
        
        $this->sessionName = $this->selectedSession->name;
        $this->sessionStartDate = $this->selectedSession->start_date->format('Y-m-d');
        $this->sessionEndDate = $this->selectedSession->end_date->format('Y-m-d');
        $this->sessionCapacity = $this->selectedSession->max_capacity;
        $this->sessionPrice = $this->selectedSession->price;
        $this->sessionDescription = $this->selectedSession->description;
        $this->sessionIsActive = $this->selectedSession->is_active;
        $this->registrationOpenDate = $this->selectedSession->registration_open_date
            ? $this->selectedSession->registration_open_date->format('Y-m-d')
            : '';
        $this->registrationCloseDate = $this->selectedSession->registration_close_date
            ? $this->selectedSession->registration_close_date->format('Y-m-d')
            : '';
        
        $this->showEditSessionModal = true;
    }

    public function openDeleteSessionModal($sessionId)
    {
        $this->selectedSession = CampInstance::findOrFail($sessionId);
        $this->showDeleteSessionModal = true;
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
            'registrationOpenDate' => 'nullable|date',
            'registrationCloseDate' => 'nullable|date|after_or_equal:registrationOpenDate',
        ]);

        $this->selectedSession->update([
            'name' => $this->sessionName,
            'start_date' => $this->sessionStartDate,
            'end_date' => $this->sessionEndDate,
            'max_capacity' => $this->sessionCapacity,
            'price' => $this->sessionPrice ?: null,
            'description' => $this->sessionDescription,
            'is_active' => $this->sessionIsActive,
            'registration_open_date' => $this->registrationOpenDate ?: null,
            'registration_close_date' => $this->registrationCloseDate ?: null,
        ]);

        $this->showEditSessionModal = false;
        $this->resetSessionForm();
        $this->selectedSession = null;
        session()->flash('message', 'Session updated successfully.');
    }

    public function deleteSession()
    {
        $sessionName = $this->selectedSession->name;
        $this->selectedSession->delete();
        
        $this->showDeleteSessionModal = false;
        $this->selectedSession = null;
        session()->flash('message', "Session '{$sessionName}' deleted successfully.");
    }

    public function resetCampForm()
    {
        $this->name = '';
        $this->displayName = '';
        $this->description = '';
        $this->isActive = true;
        $this->price = '';
        $this->selectedCamp = null;
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
        $this->registrationOpenDate = '';
        $this->registrationCloseDate = '';
        $this->selectedSession = null;
    }

    public function getCampsProperty()
    {
        $query = Camp::with(['instances' => function ($query) {
            $query->orderBy('start_date', 'desc');
        }]);

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('display_name', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query->orderBy('name')->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.camp-management', [
            'camps' => $this->camps,
        ]);
    }
}
