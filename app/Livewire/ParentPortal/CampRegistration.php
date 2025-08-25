<?php

namespace App\Livewire\ParentPortal;

use App\Models\CampInstance;
use App\Models\Enrollment;
use App\Models\Camper;
use Livewire\Component;
use Livewire\Attributes\Layout;

class CampRegistration extends Component
{
    public $campInstance;
    public $selectedCampers = [];
    public $availableCampers = [];
    public $registrationComplete = false;

    public function mount($campInstance)
    {
        $this->campInstance = CampInstance::with('camp')->findOrFail($campInstance);
        $this->loadAvailableCampers();
    }

    public function loadAvailableCampers()
    {
        $user = auth()->user();
        
        // Get campers that don't already have an enrollment for this camp instance
        $enrolledCamperIds = Enrollment::where('camp_instance_id', $this->campInstance->id)
            ->pluck('camper_id')
            ->toArray();
        
        $this->availableCampers = $user->accessibleCampers()
            ->whereNotIn('id', $enrolledCamperIds)
            ->get();
    }

    public function toggleCamperSelection($camperId)
    {
        if (in_array($camperId, $this->selectedCampers)) {
            $this->selectedCampers = array_diff($this->selectedCampers, [$camperId]);
        } else {
            $this->selectedCampers[] = $camperId;
        }
    }

    public function registerCampers()
    {
        if (empty($this->selectedCampers)) {
            $this->addError('campers', 'Please select at least one camper to register.');
            return;
        }

        foreach ($this->selectedCampers as $camperId) {
            $camper = Camper::find($camperId);
            
            if ($camper && $this->canRegisterCamper($camper)) {
                Enrollment::create([
                    'camp_instance_id' => $this->campInstance->id,
                    'camper_id' => $camperId,
                    'status' => 'pending',
                    'balance_cents' => $this->campInstance->price ? (int)($this->campInstance->price * 100) : 0,
                    'amount_paid_cents' => 0,
                    'forms_complete' => false,
                ]);
            }
        }

        $this->registrationComplete = true;
        $this->selectedCampers = [];
        $this->loadAvailableCampers();
    }

    private function canRegisterCamper($camper)
    {
        // Check if camper is already enrolled
        $existingEnrollment = Enrollment::where('camp_instance_id', $this->campInstance->id)
            ->where('camper_id', $camper->id)
            ->first();
        
        return !$existingEnrollment;
    }

    public function render()
    {
        return view('livewire.parent-portal.camp-registration')->layout('components.layouts.app');
    }
}
