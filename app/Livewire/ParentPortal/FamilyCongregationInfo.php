<?php

namespace App\Livewire\ParentPortal;

use App\Models\Family;
use App\Models\ChurchCongregation;
use Livewire\Component;

class FamilyCongregationInfo extends Component
{
    public $family;
    public $showEditModal = false;
    public $showAddCongregationModal = false;
    
    // Form fields
    public $home_congregation_id = '';
    public $search = '';
    
    // New congregation fields
    public $new_congregation_name = '';
    public $new_congregation_address = '';
    public $new_congregation_city = '';
    public $new_congregation_state = '';
    public $new_congregation_zip_code = '';
    public $new_congregation_phone = '';
    public $new_congregation_website = '';
    public $new_congregation_contact_person = '';
    public $new_congregation_contact_email = '';

    protected $rules = [
        'home_congregation_id' => 'nullable|exists:church_congregations,id',
        'new_congregation_name' => 'required_without:home_congregation_id|string|max:255',
        'new_congregation_address' => 'nullable|string|max:500',
        'new_congregation_city' => 'nullable|string|max:100',
        'new_congregation_state' => 'nullable|string|max:50',
        'new_congregation_zip_code' => 'nullable|string|max:20',
        'new_congregation_phone' => 'nullable|string|max:20',
        'new_congregation_website' => 'nullable|url|max:255',
        'new_congregation_contact_person' => 'nullable|string|max:255',
        'new_congregation_contact_email' => 'nullable|email|max:255',
    ];

    public function mount(Family $family)
    {
        $this->family = $family;
        $this->loadCongregationInfo();
    }

    public function render()
    {
        $congregations = ChurchCongregation::active()
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        return view('livewire.parent-portal.family-congregation-info', compact('congregations'));
    }

    public function edit()
    {
        $this->loadCongregationInfo();
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate([
            'home_congregation_id' => 'nullable|exists:church_congregations,id',
        ]);

        $this->family->update([
            'home_congregation_id' => $this->home_congregation_id ?: null,
        ]);

        $this->showEditModal = false;
        $this->dispatch('congregation-updated');
    }

    public function showAddCongregation()
    {
        $this->resetNewCongregationFields();
        $this->showAddCongregationModal = true;
    }

    public function addCongregation()
    {
        $this->validate([
            'new_congregation_name' => 'required|string|max:255|unique:church_congregations,name',
            'new_congregation_address' => 'nullable|string|max:500',
            'new_congregation_city' => 'nullable|string|max:100',
            'new_congregation_state' => 'nullable|string|max:50',
            'new_congregation_zip_code' => 'nullable|string|max:20',
            'new_congregation_phone' => 'nullable|string|max:20',
            'new_congregation_website' => 'nullable|url|max:255',
            'new_congregation_contact_person' => 'nullable|string|max:255',
            'new_congregation_contact_email' => 'nullable|email|max:255',
        ]);

        $congregation = ChurchCongregation::create([
            'name' => $this->new_congregation_name,
            'address' => $this->new_congregation_address,
            'city' => $this->new_congregation_city,
            'state' => $this->new_congregation_state,
            'zip_code' => $this->new_congregation_zip_code,
            'phone' => $this->new_congregation_phone,
            'website' => $this->new_congregation_website,
            'contact_person' => $this->new_congregation_contact_person,
            'contact_email' => $this->new_congregation_contact_email,
            'is_active' => true,
        ]);

        // Set the new congregation as the family's home congregation
        $this->home_congregation_id = $congregation->id;
        $this->family->update(['home_congregation_id' => $congregation->id]);

        $this->showAddCongregationModal = false;
        $this->resetNewCongregationFields();
        $this->dispatch('congregation-added');
    }

    public function selectCongregation($congregationId)
    {
        $this->home_congregation_id = $congregationId;
    }

    public function closeModal()
    {
        $this->showEditModal = false;
        $this->showAddCongregationModal = false;
    }

    private function loadCongregationInfo()
    {
        $this->home_congregation_id = $this->family->home_congregation_id ?? '';
    }

    private function resetNewCongregationFields()
    {
        $this->new_congregation_name = '';
        $this->new_congregation_address = '';
        $this->new_congregation_city = '';
        $this->new_congregation_state = '';
        $this->new_congregation_zip_code = '';
        $this->new_congregation_phone = '';
        $this->new_congregation_website = '';
        $this->new_congregation_contact_person = '';
        $this->new_congregation_contact_email = '';
    }
}
