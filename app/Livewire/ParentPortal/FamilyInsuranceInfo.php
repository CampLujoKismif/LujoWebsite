<?php

namespace App\Livewire\ParentPortal;

use App\Models\Family;
use Livewire\Component;

class FamilyInsuranceInfo extends Component
{
    public $family;
    public $showEditModal = false;
    
    // Form fields
    public $insurance_provider = '';
    public $insurance_policy_number = '';
    public $insurance_group_number = '';
    public $insurance_phone = '';

    protected $rules = [
        'insurance_provider' => 'nullable|string|max:255',
        'insurance_policy_number' => 'nullable|string|max:255',
        'insurance_group_number' => 'nullable|string|max:255',
        'insurance_phone' => 'nullable|string|max:20',
    ];

    public function mount(Family $family)
    {
        $this->family = $family;
        $this->loadInsuranceInfo();
    }

    public function render()
    {
        return view('livewire.parent-portal.family-insurance-info');
    }

    public function edit()
    {
        $this->loadInsuranceInfo();
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->family->update([
            'insurance_provider' => $this->insurance_provider,
            'insurance_policy_number' => $this->insurance_policy_number,
            'insurance_group_number' => $this->insurance_group_number,
            'insurance_phone' => $this->insurance_phone,
        ]);

        $this->showEditModal = false;
        $this->dispatch('insurance-updated');
    }

    public function closeModal()
    {
        $this->showEditModal = false;
    }

    private function loadInsuranceInfo()
    {
        $this->insurance_provider = $this->family->insurance_provider ?? '';
        $this->insurance_policy_number = $this->family->insurance_policy_number ?? '';
        $this->insurance_group_number = $this->family->insurance_group_number ?? '';
        $this->insurance_phone = $this->family->insurance_phone ?? '';
    }
}
