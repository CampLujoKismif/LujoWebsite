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
    public $statusFilter = 'active'; // all, active, deleted

    // User form properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showHardDeleteModal = false;
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
    public $availableCampRoles = [];
    public $camps = [];

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => 'active'],
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::orderBy('name')->get();
        $this->availableCampRoles = Role::where('type', 'camp_session')->orderBy('name')->get();
        $this->camps = Camp::where('is_active', true)->orderBy('display_name')->get();
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
        $this->selectedUser = User::with([
            'roles', 
            'campAssignments.camp', 
            'campAssignments.role'
        ])->findOrFail($userId);
        
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
        // Check if a soft-deleted user exists with this email
        $softDeletedUser = User::onlyTrashed()->where('email', $this->email)->first();
        
        if ($softDeletedUser) {
            // If soft-deleted user exists, validate without unique constraint
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'selectedRoles' => 'required|array|min:1',
                'selectedRoles.*' => 'exists:roles,id',
                'selectedCamps' => 'nullable|array',
                'selectedCamps.*' => 'exists:camps,id',
            ]);
        } else {
            // Normal validation with unique constraint
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'selectedRoles' => 'required|array|min:1',
                'selectedRoles.*' => 'exists:roles,id',
                'selectedCamps' => 'nullable|array',
                'selectedCamps.*' => 'exists:camps,id',
            ]);
        }

        // Custom validation for camp role assignments (role defaults to Admin if not provided)
        if (!empty($this->selectedCamps)) {
            foreach ($this->selectedCamps as $campId) {
                // If a role is provided, validate it exists
                if (isset($this->campRoles[$campId]) && !empty($this->campRoles[$campId])) {
                    if (!Role::find($this->campRoles[$campId])) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'campRoles' => 'The selected role is invalid.'
                        ]);
                    }
                }
            }
        }

        DB::transaction(function () use ($softDeletedUser) {
            if ($softDeletedUser) {
                // Restore and update the soft-deleted user
                $softDeletedUser->restore();
                $user = $softDeletedUser;
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'email_verified_at' => $this->emailVerified ? now() : null,
                    'must_change_password' => false,
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'email_verified_at' => $this->emailVerified ? now() : null,
                ]);
            }

            // Assign roles - convert IDs to names
            $roleNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);

            // Clear existing camp assignments before assigning new ones (force delete to avoid unique constraint issues)
            UserCampAssignment::where('user_id', $user->id)->forceDelete();
            
            // Assign to camps
            $this->assignUserToCamps($user);

            // Send email verification notification if email is not verified
            if (!$this->emailVerified) {
                event(new Registered($user));
            }

            $this->showCreateModal = false;
            $this->resetUserForm();
            session()->flash('message', $softDeletedUser ? 'User restored and updated successfully.' : 'User created successfully.');
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

        // Custom validation for camp role assignments (role defaults to Admin if not provided)
        if (!empty($this->selectedCamps)) {
            foreach ($this->selectedCamps as $campId) {
                // If a role is provided, validate it exists
                if (isset($this->campRoles[$campId]) && !empty($this->campRoles[$campId])) {
                    if (!Role::find($this->campRoles[$campId])) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'campRoles' => 'The selected role is invalid.'
                        ]);
                    }
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
                $updateData['must_change_password'] = true;
            } elseif (!$this->emailVerified && $this->selectedUser->email_verified_at) {
                $updateData['email_verified_at'] = null;
                $updateData['must_change_password'] = false;
            }

            $this->selectedUser->update($updateData);

            // Update roles - convert IDs to names
            $roleNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();
            $this->selectedUser->syncRoles($roleNames);

            // Update camp assignments - force delete to avoid unique constraint issues
            UserCampAssignment::where('user_id', $this->selectedUser->id)->forceDelete();
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

    public function openHardDeleteModal($userId)
    {
        $this->selectedUser = User::withTrashed()->findOrFail($userId);
        $this->showHardDeleteModal = true;
    }

    public function hardDeleteUser()
    {
        if ($this->selectedUser->id === auth()->id()) {
            session()->flash('error', 'You cannot permanently delete your own account.');
            $this->showHardDeleteModal = false;
            return;
        }

        $userName = $this->selectedUser->name;
        $this->selectedUser->forceDelete();
        $this->showHardDeleteModal = false;
        session()->flash('message', "User {$userName} has been permanently deleted.");
    }

    public function restoreUser($userId)
    {
        $user = User::onlyTrashed()->findOrFail($userId);
        $userName = $user->name;
        $user->restore();
        session()->flash('message', "User {$userName} has been restored.");
    }

    public function resendVerification($userId)
    {
        $user = User::findOrFail($userId);
        
        // Only resend if email is not already verified
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            session()->flash('message', 'Verification email sent to ' . $user->email . '.');
        } else {
            session()->flash('info', 'User email is already verified.');
        }
    }

    private function assignUserToCamps($user)
    {
        // Get default Admin role for camp assignments
        $defaultRole = Role::where('type', 'camp_session')
            ->where('name', 'admin')
            ->first();
        
        if (!$defaultRole) {
            // Create default Admin role if it doesn't exist
            $defaultRole = Role::create([
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Default admin role for camp assignments',
                'type' => 'camp_session',
                'is_admin' => false,
            ]);
        }
        
        foreach ($this->selectedCamps as $campId) {
            try {
                $isPrimary = in_array($campId, $this->primaryCamps);
                
                // Check if camp exists
                $camp = Camp::find($campId);
                
                if (!$camp) {
                    throw new \Exception("Camp with ID {$campId} not found.");
                }
                
                // Get role ID - use provided role or default to Admin
                $roleId = $defaultRole->id;
                if (isset($this->campRoles[$campId]) && !empty($this->campRoles[$campId])) {
                    $role = Role::find($this->campRoles[$campId]);
                    
                    if (!$role) {
                        throw new \Exception("Role with ID {$this->campRoles[$campId]} not found.");
                    }
                    
                    $roleId = $role->id;
                }
                
                // Create new assignment (old ones were force-deleted, so this is safe)
                $user->campAssignments()->create([
                    'camp_id' => $campId,
                    'role_id' => $roleId,
                    'is_primary' => $isPrimary,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Log the actual database error for debugging
                \Log::error('Failed to assign user to camp', [
                    'user_id' => $user->id,
                    'camp_id' => $campId,
                    'role_id' => $this->campRoles[$campId] ?? null,
                    'error' => $e->getMessage(),
                ]);
                throw new \Exception('Failed to assign user to camp: ' . $e->getMessage());
            } catch (\Exception $e) {
                // Log other errors
                \Log::error('Error assigning user to camp', [
                    'user_id' => $user->id,
                    'camp_id' => $campId,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
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
        \Log::info("UserManagement: Starting getUsersProperty", [
            'statusFilter' => $this->statusFilter,
            'searchTerm' => $this->searchTerm,
            'roleFilter' => $this->roleFilter
        ]);

        try {
            // Handle soft-deleted users based on filter
            $query = null;
            if ($this->statusFilter === 'deleted') {
                $query = User::onlyTrashed();
                \Log::info("UserManagement: Using onlyTrashed()");
            } elseif ($this->statusFilter === 'all') {
                $query = User::withTrashed();
                \Log::info("UserManagement: Using withTrashed()");
            } else {
                $query = User::query();
                \Log::info("UserManagement: Using query() - active users only");
            }

            if (!empty($this->searchTerm)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                });
                \Log::info("UserManagement: Added search filter");
            }

            if (!empty($this->roleFilter)) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
                \Log::info("UserManagement: Added role filter");
            }

            // Eager load relationships
            $query->with(['roles', 'campAssignments.camp', 'campAssignments.role']);
            \Log::info("UserManagement: Added eager loading");

            $result = $query->orderBy('name')->paginate(15);
            \Log::info("UserManagement: Query successful", ['count' => $result->count()]);
            
            return $result;
        } catch (\Exception $e) {
            \Log::error("UserManagement: Error in getUsersProperty", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => $this->users,
        ]);
    }
}
