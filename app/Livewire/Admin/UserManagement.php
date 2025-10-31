<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use App\Models\Camp;
use App\Models\UserCampAssignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class UserManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $searchTerm = '';
    public $roleFilter = '';
    public $statusFilter = '';

    // User form properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $selectedUser = null;

    // User form data
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    public $emailVerified = false;
    public $selectedCamps = [];
    public $campRoles = [];
    public $primaryCamps = [];

    // Available data
    public $roles = [];
    public $camps = [];

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::orderBy('name')->get();
        $this->camps = Camp::where('is_active', true)->orderBy('name')->get();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetUserForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($userId)
    {
        $this->selectedUser = User::with(['roles', 'campAssignments.camp', 'campAssignments.role'])->findOrFail($userId);
        
        $this->name = $this->selectedUser->name;
        $this->email = $this->selectedUser->email;
        $this->selectedRoles = $this->selectedUser->roles->pluck('id')->toArray();
        $this->emailVerified = !is_null($this->selectedUser->email_verified_at);
        
        // Load camp assignments
        $this->selectedCamps = $this->selectedUser->campAssignments->pluck('camp_id')->toArray();
        $this->campRoles = [];
        $this->primaryCamps = [];
        
        foreach ($this->selectedUser->campAssignments as $assignment) {
            $this->campRoles[$assignment->camp_id] = $assignment->role_id;
            if ($assignment->is_primary) {
                $this->primaryCamps[] = $assignment->camp_id;
            }
        }
        
        $this->showEditModal = true;
    }

    public function openDeleteModal($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
            'selectedCamps' => 'nullable|array',
            'selectedCamps.*' => 'exists:camps,id',
        ]);

        // Custom validation for camp role assignments
        if (!empty($this->selectedCamps)) {
            foreach ($this->selectedCamps as $campId) {
                if (!isset($this->campRoles[$campId]) || empty($this->campRoles[$campId])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'campRoles' => 'A role must be selected for each camp assignment.'
                    ]);
                }
                if (!Role::find($this->campRoles[$campId])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'campRoles' => 'The selected role is invalid.'
                    ]);
                }
            }
        }

        DB::transaction(function () {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'email_verified_at' => $this->emailVerified ? now() : null,
            ]);

            // Assign roles - convert IDs to names
            $roleNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);

            // Assign to camps
            $this->assignUserToCamps($user);

            // Send email verification notification if email is not verified
            if (!$this->emailVerified) {
                event(new Registered($user));
            }

            $this->showCreateModal = false;
            $this->resetUserForm();
            session()->flash('message', 'User created successfully.');
        });
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->selectedUser->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
            'selectedCamps' => 'nullable|array',
            'selectedCamps.*' => 'exists:camps,id',
        ]);

        // Custom validation for camp role assignments
        if (!empty($this->selectedCamps)) {
            foreach ($this->selectedCamps as $campId) {
                if (!isset($this->campRoles[$campId]) || empty($this->campRoles[$campId])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'campRoles' => 'A role must be selected for each camp assignment.'
                    ]);
                }
                if (!Role::find($this->campRoles[$campId])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'campRoles' => 'The selected role is invalid.'
                    ]);
                }
            }
        }

        DB::transaction(function () {
            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            if ($this->emailVerified && !$this->selectedUser->email_verified_at) {
                $updateData['email_verified_at'] = now();
            } elseif (!$this->emailVerified && $this->selectedUser->email_verified_at) {
                $updateData['email_verified_at'] = null;
            }

            $this->selectedUser->update($updateData);

            // Update roles - convert IDs to names
            $roleNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();
            $this->selectedUser->syncRoles($roleNames);

            // Update camp assignments
            $this->selectedUser->campAssignments()->delete();
            $this->assignUserToCamps($this->selectedUser);

            $this->showEditModal = false;
            $this->resetUserForm();
            session()->flash('message', 'User updated successfully.');
        });
    }

    public function deleteUser()
    {
        if ($this->selectedUser->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->showDeleteModal = false;
            return;
        }

        $this->selectedUser->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'User deleted successfully.');
    }

    private function assignUserToCamps($user)
    {
        foreach ($this->selectedCamps as $campId) {
            if (isset($this->campRoles[$campId]) && !empty($this->campRoles[$campId])) {
                try {
                    $user->campAssignments()->create([
                        'camp_id' => $campId,
                        'role_id' => $this->campRoles[$campId],
                        'is_primary' => in_array($campId, $this->primaryCamps),
                    ]);
                } catch (\Exception $e) {
                    throw new \Exception('Failed to assign user to camp. Please try again.');
                }
            }
        }
    }

    public function resetUserForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = [];
        $this->emailVerified = false;
        $this->selectedCamps = [];
        $this->campRoles = [];
        $this->primaryCamps = [];
        $this->selectedUser = null;
    }

    public function getUsersProperty()
    {
        $query = User::with(['roles', 'campAssignments.camp']);

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if (!empty($this->roleFilter)) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        if (!empty($this->statusFilter)) {
            if ($this->statusFilter === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($this->statusFilter === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        return $query->orderBy('name')->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => $this->users,
        ]);
    }
}
