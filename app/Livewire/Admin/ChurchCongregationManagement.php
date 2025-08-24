<?php

namespace App\Livewire\Admin;

use App\Models\ChurchCongregation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ChurchCongregationManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $status = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingCongregation = null;

    // Form fields
    public $name = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zip_code = '';
    public $phone = '';
    public $website = '';
    public $contact_person = '';
    public $contact_email = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:50',
        'zip_code' => 'nullable|string|max:20',
        'phone' => 'nullable|string|max:20',
        'website' => 'nullable|url|max:255',
        'contact_person' => 'nullable|string|max:255',
        'contact_email' => 'nullable|email|max:255',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $query = ChurchCongregation::query();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->status) {
            if ($this->status === 'active') {
                $query->active();
            } elseif ($this->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $congregations = $query->orderBy('name')->paginate(15);

        return view('livewire.admin.church-congregation-management', compact('congregations'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:church_congregations',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        ChurchCongregation::create([
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'phone' => $this->phone,
            'website' => $this->website,
            'contact_person' => $this->contact_person,
            'contact_email' => $this->contact_email,
            'is_active' => $this->is_active,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        $this->dispatch('congregation-created');
    }

    public function edit(ChurchCongregation $congregation)
    {
        $this->editingCongregation = $congregation;
        $this->name = $congregation->name;
        $this->address = $congregation->address;
        $this->city = $congregation->city;
        $this->state = $congregation->state;
        $this->zip_code = $congregation->zip_code;
        $this->phone = $congregation->phone;
        $this->website = $congregation->website;
        $this->contact_person = $congregation->contact_person;
        $this->contact_email = $congregation->contact_email;
        $this->is_active = $congregation->is_active;
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:church_congregations,name,' . $this->editingCongregation->id,
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $this->editingCongregation->update([
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'phone' => $this->phone,
            'website' => $this->website,
            'contact_person' => $this->contact_person,
            'contact_email' => $this->contact_email,
            'is_active' => $this->is_active,
        ]);

        $this->showEditModal = false;
        $this->resetForm();
        $this->dispatch('congregation-updated');
    }

    public function delete(ChurchCongregation $congregation)
    {
        if ($congregation->families()->count() > 0) {
            $this->dispatch('error', 'Cannot delete congregation that has associated families.');
            return;
        }

        $congregation->delete();
        $this->dispatch('congregation-deleted');
    }

    public function toggleStatus(ChurchCongregation $congregation)
    {
        $congregation->update(['is_active' => !$congregation->is_active]);
        $this->dispatch('congregation-updated');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->address = '';
        $this->city = '';
        $this->state = '';
        $this->zip_code = '';
        $this->phone = '';
        $this->website = '';
        $this->contact_person = '';
        $this->contact_email = '';
        $this->is_active = true;
        $this->editingCongregation = null;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }
}
