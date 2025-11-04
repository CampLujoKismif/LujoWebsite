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
    public $showDeleteModal = false;
    public $showDetailsModal = false;
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

    // Session detail form data
    public $themeDescription = '';
    public $additionalInfo = '';
    public $scheduleData = [];
    public $themePhoto = '';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'statusFilter' => ['except' => 'active'], // Default to 'active'
        'campFilter' => ['except' => ''],
    ];

    public function mount()
    {
        // Set default camp filter to first assigned camp if available
        if ($this->assignedCamps->isNotEmpty() && empty($this->campFilter)) {
            $this->campFilter = $this->assignedCamps->first()->id;
        }
        
        // Set default status filter to 'active' if not set
        if (empty($this->statusFilter)) {
            $this->statusFilter = 'active';
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

    public function openDetailsModal($sessionId)
    {
        $this->selectedSession = CampInstance::findOrFail($sessionId);
        
        // Check if user has access to this session's camp
        if (!Auth::user()->canAccessCampData($this->selectedSession->camp_id)) {
            session()->flash('error', 'You do not have permission to edit this session.');
            return;
        }
        
        // Load session fields
        $this->sessionName = $this->selectedSession->name;
        $this->sessionStartDate = $this->selectedSession->start_date->format('Y-m-d');
        $this->sessionEndDate = $this->selectedSession->end_date->format('Y-m-d');
        $this->sessionCapacity = $this->selectedSession->max_capacity;
        $this->sessionPrice = $this->selectedSession->price;
        $this->sessionDescription = $this->selectedSession->description;
        $this->sessionIsActive = $this->selectedSession->is_active;
        
        // Load detail fields
        $this->themeDescription = $this->selectedSession->theme_description ?? '';
        
        // Handle additional_info - it can be stored as array or string
        if ($this->selectedSession->additional_info) {
            if (is_array($this->selectedSession->additional_info)) {
                // If it's an array, try to get the 'content' key, otherwise convert to string
                $this->additionalInfo = $this->selectedSession->additional_info['content'] ?? 
                    (is_string($this->selectedSession->additional_info) ? $this->selectedSession->additional_info : '');
            } else {
                $this->additionalInfo = $this->selectedSession->additional_info;
            }
        } else {
            $this->additionalInfo = '';
        }
        
        // Load schedule_data - convert from key-value pairs to array format
        $this->scheduleData = [];
        if ($this->selectedSession->schedule_data && is_array($this->selectedSession->schedule_data)) {
            foreach ($this->selectedSession->schedule_data as $time => $activity) {
                $this->scheduleData[] = ['time' => $time, 'activity' => $activity];
            }
        }
        
        // Load theme_photo (single photo)
        $themePhotos = $this->selectedSession->theme_photos ?? null;
        if (is_array($themePhotos) && count($themePhotos) > 0) {
            // If stored as array, take the first one
            $this->themePhoto = $themePhotos[0];
        } elseif (is_string($themePhotos)) {
            // If stored as string, use it directly
            $this->themePhoto = $themePhotos;
        } else {
            $this->themePhoto = '';
        }
        
        $this->showDetailsModal = true;
        
        // Dispatch event to trigger Vue component mounting after modal is rendered
        $this->dispatch('modal-opened');
    }

    public function addScheduleItem()
    {
        $this->scheduleData[] = ['time' => '', 'activity' => ''];
    }

    public function removeScheduleItem($index)
    {
        unset($this->scheduleData[$index]);
        $this->scheduleData = array_values($this->scheduleData); // Re-index array
    }

    public function addPhotoUrl($url)
    {
        if (!empty($url)) {
            $this->themePhoto = $url;
        }
    }

    public function removePhotoUrl()
    {
        $this->themePhoto = '';
    }

    public function updateSessionDetails()
    {
        $this->validate([
            'sessionName' => 'required|string|max:255',
            'sessionStartDate' => 'required|date',
            'sessionEndDate' => 'required|date|after:sessionStartDate',
            'sessionCapacity' => 'required|integer|min:1',
            'sessionPrice' => 'nullable|numeric|min:0',
            'sessionDescription' => 'nullable|string',
            'themeDescription' => 'nullable|string',
            'additionalInfo' => 'nullable|string',
            'scheduleData' => 'nullable|array',
            'scheduleData.*.time' => 'required_with:scheduleData|string',
            'scheduleData.*.activity' => 'required_with:scheduleData|string',
            'themePhoto' => 'nullable|string',
        ]);

        // Process schedule_data - convert from array format to key-value pairs
        $scheduleData = null;
        if (!empty($this->scheduleData)) {
            $scheduleArray = [];
            foreach ($this->scheduleData as $item) {
                if (!empty($item['time']) && !empty($item['activity'])) {
                    $scheduleArray[$item['time']] = $item['activity'];
                }
            }
            $scheduleData = !empty($scheduleArray) ? $scheduleArray : null;
        }

        // Process theme_photo - store as single photo (array with one element for backward compatibility)
        $themePhotos = null;
        if (!empty($this->themePhoto)) {
            $themePhotos = [$this->themePhoto];
        }

        // Handle additional_info - store as array format for consistency
        $additionalInfo = null;
        if (!empty($this->additionalInfo)) {
            $additionalInfo = ['content' => $this->additionalInfo];
        }

        // Update the session with all fields
        $this->selectedSession->update([
            'name' => $this->sessionName,
            'start_date' => $this->sessionStartDate,
            'end_date' => $this->sessionEndDate,
            'max_capacity' => $this->sessionCapacity,
            'price' => $this->sessionPrice ?: null,
            'description' => $this->sessionDescription,
            'is_active' => $this->sessionIsActive,
            'theme_description' => $this->themeDescription ?: null,
            'additional_info' => $additionalInfo,
            'schedule_data' => $scheduleData,
            'theme_photos' => $themePhotos,
        ]);

        // Refresh the instance to ensure fresh data
        $this->selectedSession->refresh();

        $this->showDetailsModal = false;
        $this->resetDetailsForm();
        
        // Reset pagination to refresh the sessions list
        $this->resetPage();
        
        session()->flash('message', 'Session updated successfully.');
    }

    public function resetDetailsForm()
    {
        $this->sessionName = '';
        $this->sessionStartDate = '';
        $this->sessionEndDate = '';
        $this->sessionCapacity = '';
        $this->sessionPrice = '';
        $this->sessionDescription = '';
        $this->sessionIsActive = true;
        $this->themeDescription = '';
        $this->additionalInfo = '';
        $this->scheduleData = [];
        $this->themePhoto = '';
        $this->selectedSession = null;
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
