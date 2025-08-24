<?php

namespace App\Livewire\ParentPortal;

use App\Models\Family;
use Livewire\Component;

class FamilyDetails extends Component
{
    public $family;
    public $activeTab = 'overview';

    public function mount(Family $family)
    {
        $this->family = $family;
    }

    public function render()
    {
        return view('livewire.parent-portal.family-details');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }
}
