<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;

class RoleManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $searchTerm = '';
    public $typeFilter = '';
    public $adminFilter = '';

    // Role form properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $selectedRole = null;

    // Role form data
    public $name = '';
    public $display_name = '';
    public $description = '';
    public $is_admin = false;
    public $type = 'web_access';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'adminFilter' => ['except' => ''],
    ];

    // System roles that cannot be deleted
    protected $systemRoles = [
        'super_admin',
        'camp_director',
        'board_member',
        'camp_staff',
        'youth_minister',
        'church_representative',
        'parent',
        'camper',
        'system-admin',
        'camp-manager',
        'rental-admin'
    ];

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedAdminFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetRoleForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($roleId)
    {
        $this->selectedRole = Role::findOrFail($roleId);
        
        $this->name = $this->selectedRole->name;
        $this->display_name = $this->selectedRole->display_name;
        $this->description = $this->selectedRole->description ?? '';
        $this->is_admin = $this->selectedRole->is_admin ?? false;
        $this->type = $this->selectedRole->type ?? Role::TYPE_WEB_ACCESS;
        
        $this->showEditModal = true;
    }

    public function openDeleteModal($roleId)
    {
        $this->selectedRole = Role::findOrFail($roleId);
        
        // Check if it's a system role
        if (in_array($this->selectedRole->name, $this->systemRoles)) {
            session()->flash('error', 'Cannot delete system roles.');
            return;
        }
        
        // Check if role has users
        if ($this->selectedRole->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role with assigned users. Please remove users from this role first.');
            return;
        }
        
        $this->showDeleteModal = true;
    }

    public function createRole()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_admin' => 'boolean',
            'type' => 'required|string|in:web_access,camp_session',
        ]);

        Role::create([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'is_admin' => $this->is_admin,
            'type' => $this->type,
        ]);

        $this->showCreateModal = false;
        $this->resetRoleForm();
        session()->flash('message', 'Role created successfully.');
    }

    public function updateRole()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $this->selectedRole->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_admin' => 'boolean',
            'type' => 'required|string|in:web_access,camp_session',
        ]);

        $this->selectedRole->update([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'is_admin' => $this->is_admin,
            'type' => $this->type,
        ]);

        $this->showEditModal = false;
        $this->resetRoleForm();
        session()->flash('message', 'Role updated successfully.');
    }

    public function deleteRole()
    {
        // Check if it's a system role
        if (in_array($this->selectedRole->name, $this->systemRoles)) {
            session()->flash('error', 'Cannot delete system roles.');
            $this->showDeleteModal = false;
            return;
        }

        // Check if role has users
        if ($this->selectedRole->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role with assigned users. Please remove users from this role first.');
            $this->showDeleteModal = false;
            return;
        }

        $this->selectedRole->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'Role deleted successfully.');
    }

    public function resetRoleForm()
    {
        $this->name = '';
        $this->display_name = '';
        $this->description = '';
        $this->is_admin = false;
        $this->type = 'web_access';
        $this->selectedRole = null;
    }

    public function getRolesProperty()
    {
        $query = Role::query();

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('display_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Filter by role type
        if (!empty($this->typeFilter)) {
            $query->where('type', $this->typeFilter);
        }

        // Filter by admin status
        if (!empty($this->adminFilter)) {
            if ($this->adminFilter === 'admin') {
                $query->where('is_admin', true);
            } elseif ($this->adminFilter === 'non_admin') {
                $query->where('is_admin', false);
            }
        }

        // Eager load relationships for counts
        $query->withCount(['users', 'permissions']);

        return $query->orderBy('display_name')->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.role-management', [
            'roles' => $this->roles,
        ]);
    }
}