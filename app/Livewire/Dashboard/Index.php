<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Index extends Component
{
    public function mount()
    {
        $user = auth()->user();
        
        // Redirect users based on their primary role
        if ($user->hasRole('system-admin')) {
            return $this->redirect(route('dashboard.admin.index'), navigate: true);
        }
        
        if ($user->hasRole('camp-manager')) {
            return $this->redirect(route('dashboard.manager.index'), navigate: true);
        }
        
        if ($user->hasRole('parent')) {
            return $this->redirect(route('dashboard.parent.index'), navigate: true);
        }
    }

    public function render()
    {
        // Default dashboard for users without specific roles
        return view('livewire.dashboard.index');
    }
}