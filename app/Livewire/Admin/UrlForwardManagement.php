<?php

namespace App\Livewire\Admin;

use App\Models\UrlForward;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class UrlForwardManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingUrlForward = null;

    // Form fields
    public $internal_url = '';
    public $external_url = '';
    public $title = '';
    public $description = '';
    public $is_active = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    protected $rules = [
        'internal_url' => 'required|string|max:255',
        'external_url' => 'required|url|max:500',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(UrlForward $urlForward)
    {
        $this->editingUrlForward = $urlForward;
        $this->internal_url = $urlForward->internal_url;
        $this->external_url = $urlForward->external_url;
        $this->title = $urlForward->title;
        $this->description = $urlForward->description;
        $this->is_active = $urlForward->is_active;
        $this->showEditModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->internal_url = '';
        $this->external_url = '';
        $this->title = '';
        $this->description = '';
        $this->is_active = true;
        $this->editingUrlForward = null;
    }

    public function store()
    {
        $this->validate([
            'internal_url' => 'required|string|max:255|unique:url_forwards',
            'external_url' => 'required|url|max:500',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Clean internal URL (remove leading slash)
        $internalUrl = ltrim($this->internal_url, '/');

        UrlForward::create([
            'internal_url' => $internalUrl,
            'external_url' => $this->external_url,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_by' => auth()->id(),
        ]);

        $this->closeModal();
        session()->flash('success', 'URL forward created successfully.');
    }

    public function update()
    {
        $this->validate([
            'internal_url' => 'required|string|max:255|unique:url_forwards,internal_url,' . $this->editingUrlForward->id,
            'external_url' => 'required|url|max:500',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Clean internal URL (remove leading slash)
        $internalUrl = ltrim($this->internal_url, '/');

        $this->editingUrlForward->update([
            'internal_url' => $internalUrl,
            'external_url' => $this->external_url,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->closeModal();
        session()->flash('success', 'URL forward updated successfully.');
    }

    public function delete(UrlForward $urlForward)
    {
        $urlForward->delete();
        session()->flash('success', 'URL forward deleted successfully.');
    }

    public function toggleStatus(UrlForward $urlForward)
    {
        $urlForward->update(['is_active' => !$urlForward->is_active]);
        $status = $urlForward->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "URL forward {$status} successfully.");
    }

    public function render()
    {
        $query = UrlForward::with('creator');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('internal_url', 'like', "%{$this->search}%")
                  ->orWhere('external_url', 'like', "%{$this->search}%")
                  ->orWhere('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->status === 'active') {
            $query->where('is_active', true);
        } elseif ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        $urlForwards = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.admin.url-forward-management', compact('urlForwards'));
    }
}
