<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DiscountCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DiscountCodeManagement extends Component
{
    use WithPagination, AuthorizesRequests;

    // Search and filters
    public $searchTerm = '';
    public $typeFilter = '';
    public $statusFilter = '';
    public $discountTypeFilter = '';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $selectedDiscount = null;

    // Form data
    public $code = '';
    public $type = 'rental';
    public $discountType = 'percentage';
    public $discountValue = '';
    public $maxUses = '';
    public $validFrom = '';
    public $validUntil = '';
    public $description = '';
    public $isActive = true;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'discountTypeFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->authorize('viewAny', DiscountCode::class);
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDiscountTypeFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->authorize('create', DiscountCode::class);
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($discountId)
    {
        $this->selectedDiscount = DiscountCode::findOrFail($discountId);
        $this->authorize('update', $this->selectedDiscount);

        $this->code = $this->selectedDiscount->code;
        $this->type = $this->selectedDiscount->type;
        $this->discountType = $this->selectedDiscount->discount_type;
        $this->discountValue = $this->selectedDiscount->discount_value;
        $this->maxUses = $this->selectedDiscount->max_uses;
        $this->validFrom = $this->selectedDiscount->valid_from ? $this->selectedDiscount->valid_from->format('Y-m-d') : '';
        $this->validUntil = $this->selectedDiscount->valid_until ? $this->selectedDiscount->valid_until->format('Y-m-d') : '';
        $this->description = $this->selectedDiscount->description ?? '';
        $this->isActive = $this->selectedDiscount->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($discountId)
    {
        $this->selectedDiscount = DiscountCode::findOrFail($discountId);
        $this->authorize('delete', $this->selectedDiscount);
        $this->showDeleteModal = true;
    }

    public function createDiscountCode()
    {
        $this->authorize('create', DiscountCode::class);

        $this->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code',
            'type' => 'required|in:rental,camper',
            'discountType' => 'required|in:percentage,fixed',
            'discountValue' => 'required|numeric|min:0',
            'maxUses' => 'nullable|integer|min:1',
            'validFrom' => 'nullable|date',
            'validUntil' => 'nullable|date|after:validFrom',
            'description' => 'nullable|string|max:255',
        ]);

        DiscountCode::create([
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
            'max_uses' => $this->maxUses ?: null,
            'valid_from' => $this->validFrom ?: null,
            'valid_until' => $this->validUntil ?: null,
            'description' => $this->description ?: null,
            'is_active' => $this->isActive,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'Discount code created successfully.');
    }

    public function updateDiscountCode()
    {
        $this->authorize('update', $this->selectedDiscount);

        $this->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code,' . $this->selectedDiscount->id,
            'type' => 'required|in:rental,camper',
            'discountType' => 'required|in:percentage,fixed',
            'discountValue' => 'required|numeric|min:0',
            'maxUses' => 'nullable|integer|min:1',
            'validFrom' => 'nullable|date',
            'validUntil' => 'nullable|date|after:validFrom',
            'description' => 'nullable|string|max:255',
        ]);

        $this->selectedDiscount->update([
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
            'max_uses' => $this->maxUses ?: null,
            'valid_from' => $this->validFrom ?: null,
            'valid_until' => $this->validUntil ?: null,
            'description' => $this->description ?: null,
            'is_active' => $this->isActive,
        ]);

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('message', 'Discount code updated successfully.');
    }

    public function deleteDiscountCode()
    {
        $this->authorize('delete', $this->selectedDiscount);

        $codeName = $this->selectedDiscount->code;
        $this->selectedDiscount->delete();

        $this->showDeleteModal = false;
        $this->selectedDiscount = null;
        session()->flash('message', "Discount code '{$codeName}' deleted successfully.");
    }

    public function toggleStatus($discountId)
    {
        $discount = DiscountCode::findOrFail($discountId);
        $this->authorize('toggle', $discount);

        $discount->update(['is_active' => !$discount->is_active]);
        session()->flash('message', $discount->is_active ? 'Discount code activated successfully.' : 'Discount code deactivated successfully.');
    }

    public function resetForm()
    {
        $this->code = '';
        $this->type = 'rental';
        $this->discountType = 'percentage';
        $this->discountValue = '';
        $this->maxUses = '';
        $this->validFrom = '';
        $this->validUntil = '';
        $this->description = '';
        $this->isActive = true;
        $this->selectedDiscount = null;
    }

    public function getDiscountCodesProperty()
    {
        $query = DiscountCode::query();

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('code', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if (!empty($this->typeFilter)) {
            $query->where('type', $this->typeFilter);
        }

        if (!empty($this->statusFilter)) {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        if (!empty($this->discountTypeFilter)) {
            $query->where('discount_type', $this->discountTypeFilter);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function render()
    {
        return view('livewire.admin.discount-code-management', [
            'discountCodes' => $this->discountCodes,
        ]);
    }
}

