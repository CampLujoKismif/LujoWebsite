<?php

namespace App\Livewire\Admin;

use App\Models\FormResponse;
use App\Models\FormTemplate;
use App\Models\Camper;
use App\Models\CampInstance;
use Livewire\Component;
use Livewire\WithPagination;

class FormResponseManagement extends Component
{
    use WithPagination;

    public $responses;
    public $templates;
    public $campInstances;
    public $selectedTemplate;
    public $selectedCampInstance;
    public $selectedCamper;
    public $showResponseModal = false;
    public $selectedResponse;
    
    // Filters
    public $templateFilter = '';
    public $statusFilter = '';
    public $campInstanceFilter = '';
    public $searchTerm = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->templates = FormTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $this->campInstances = CampInstance::where('is_active', true)
            ->with('camp')
            ->orderBy('start_date')
            ->get();
    }

    public function updatedTemplateFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedCampInstanceFilter()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function openResponseModal(FormResponse $response)
    {
        $this->selectedResponse = $response->load(['formTemplate.fields', 'camper.family', 'answers.formField']);
        $this->showResponseModal = true;
    }

    public function markAsComplete(FormResponse $response)
    {
        $response->update(['is_complete' => true]);
        $this->dispatch('response-updated');
    }

    public function markAsIncomplete(FormResponse $response)
    {
        $response->update(['is_complete' => false]);
        $this->dispatch('response-updated');
    }

    public function deleteResponse(FormResponse $response)
    {
        $response->answers()->delete();
        $response->delete();
        $this->dispatch('response-deleted');
    }

    public function getResponsesProperty()
    {
        $query = FormResponse::with([
            'formTemplate',
            'camper.family',
            'enrollment.campInstance.camp',
            'submittedBy'
        ]);

        // Apply filters
        if ($this->templateFilter) {
            $query->where('form_template_id', $this->templateFilter);
        }

        if ($this->statusFilter) {
            if ($this->statusFilter === 'complete') {
                $query->where('is_complete', true);
            } elseif ($this->statusFilter === 'incomplete') {
                $query->where('is_complete', false);
            }
        }

        if ($this->campInstanceFilter) {
            $query->whereHas('enrollment', function ($q) {
                $q->where('camp_instance_id', $this->campInstanceFilter);
            });
        }

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->whereHas('camper', function ($subQ) {
                    $subQ->where('first_name', 'like', '%' . $this->searchTerm . '%')
                         ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                })
                ->orWhereHas('formTemplate', function ($subQ) {
                    $subQ->where('name', 'like', '%' . $this->searchTerm . '%');
                });
            });
        }

        return $query->orderBy('submitted_at', 'desc')->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.form-response-management')->layout('components.layouts.app');
    }
}
