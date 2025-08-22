<?php

namespace App\Livewire\ParentPortal;

use App\Models\Camper;
use App\Models\MedicalRecord;
use Livewire\Component;

class MedicalRecords extends Component
{
    public $campers;
    public $selectedCamper;
    public $medicalRecord;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        $this->campers = $user->accessibleCampers()->with(['medicalRecord', 'family'])->get();
    }

    public function selectCamper($camperId)
    {
        $this->selectedCamper = $this->campers->find($camperId);
        $this->medicalRecord = $this->selectedCamper->medicalRecord;
    }

    public function render()
    {
        return view('livewire.parent-portal.medical-records')->layout('components.layouts.app');
    }
}
