<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RentalReservation;
use App\Models\RentalPricing;
use App\Models\RentalBlackoutDate;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class RentalManagement extends Component
{
    use WithPagination, AuthorizesRequests;

    // Search and filters
    public $searchTerm = '';
    public $statusFilter = '';
    public $dateFilter = 'upcoming';
    public $paymentMethodFilter = '';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showCancelModal = false;
    public $showRefundModal = false;
    public $showPricingModal = false;
    public $showDiscountModal = false;
    public $showPaymentModal = false;
    public $showBlackoutModal = false;
    public $showBlackoutEditModal = false;
    public $showBlackoutDeleteModal = false;

    // Selected items
    public $selectedReservation = null;
    public $selectedDiscount = null;
    public $selectedBlackout = null;

    // Form data
    public $reservationData = [];
    public $pricingData = [];
    public $discountData = [];
    public $paymentData = [];
    public $blackoutData = [];
    
    // Pricing options
    public $useCustomPricing = false;
    public $calculatedTotal = 0;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => 'upcoming'],
        'paymentMethodFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->authorize('viewAny', RentalReservation::class);
        $this->resetFormData();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function updatedPaymentMethodFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetFormData();
        $this->showCreateModal = true;
    }

    public function openEditModal($reservationId)
    {
        $this->selectedReservation = RentalReservation::findOrFail($reservationId);
        $this->reservationData = [
            'contact_name' => $this->selectedReservation->contact_name,
            'contact_email' => $this->selectedReservation->contact_email,
            'contact_phone' => $this->selectedReservation->contact_phone,
            'rental_purpose' => $this->selectedReservation->rental_purpose,
            'number_of_people' => $this->selectedReservation->number_of_people,
            'start_date' => $this->selectedReservation->start_date->format('Y-m-d'),
            'end_date' => $this->selectedReservation->end_date->format('Y-m-d'),
            'status' => $this->selectedReservation->status,
            'total_amount' => $this->selectedReservation->total_amount,
            'deposit_amount' => $this->selectedReservation->deposit_amount,
            'final_amount' => $this->selectedReservation->final_amount,
            'notes' => $this->selectedReservation->notes,
        ];
        $this->showEditModal = true;
    }

    public function openCancelModal($reservationId)
    {
        $this->selectedReservation = RentalReservation::findOrFail($reservationId);
        $this->showCancelModal = true;
    }

    public function openRefundModal($reservationId)
    {
        $this->selectedReservation = RentalReservation::findOrFail($reservationId);
        $this->showRefundModal = true;
    }

    public function openPricingModal()
    {
        $pricing = RentalPricing::current();
        $this->pricingData = [
            'price_per_person_per_day' => $pricing ? $pricing->price_per_person_per_day : 0,
            'deposit_amount' => $pricing ? $pricing->deposit_amount : 0,
            'description' => $pricing ? $pricing->description : '',
        ];
        $this->showPricingModal = true;
    }

    public function openDiscountModal()
    {
        $this->resetFormData();
        $this->showDiscountModal = true;
    }

    public function openDeleteModal($reservationId)
    {
        $this->selectedReservation = RentalReservation::findOrFail($reservationId);
        $this->showDeleteModal = true;
    }

    public function createReservation()
    {
        $this->authorize('create', RentalReservation::class);
        
        $rules = [
            'reservationData.contact_name' => 'required|string|max:255',
            'reservationData.contact_email' => 'required|email|max:255',
            'reservationData.contact_phone' => 'required|string|max:20',
            'reservationData.rental_purpose' => 'required|string|max:1000',
            'reservationData.number_of_people' => 'required|integer|min:1|max:100',
            'reservationData.start_date' => 'required|date|after:today',
            'reservationData.end_date' => 'required|date|after:start_date',
        ];

        // Add custom pricing validation if enabled
        if ($this->useCustomPricing) {
            $rules['reservationData.custom_total'] = 'required|numeric|min:0';
            $rules['reservationData.custom_deposit'] = 'nullable|numeric|min:0';
        }

        $this->validate($rules);

        $startDate = Carbon::parse($this->reservationData['start_date']);
        $endDate = Carbon::parse($this->reservationData['end_date']);
        
        // Check for blackout dates
        if (RentalBlackoutDate::hasConflict($startDate, $endDate)) {
            $conflicts = RentalBlackoutDate::getConflicts($startDate, $endDate);
            $conflictMessages = $conflicts->map(function ($blackout) {
                return $blackout->reason . ' (' . $blackout->start_date->format('M j, Y') . ' - ' . $blackout->end_date->format('M j, Y') . ')';
            })->implode(', ');
            
            session()->flash('error', 'Cannot create reservation. The selected dates conflict with blackout period(s): ' . $conflictMessages);
            return;
        }
        
        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        // Determine pricing
        if ($this->useCustomPricing) {
            $baseTotal = $this->reservationData['custom_total'];
            $depositAmount = $this->reservationData['custom_deposit'] ?? null;
        } else {
            // Calculate pricing
            $pricing = RentalPricing::current();
            if (!$pricing) {
                session()->flash('error', 'No active pricing found. Please set up pricing first or use custom pricing.');
                return;
            }
            
            $baseTotal = $pricing->calculateTotal($this->reservationData['number_of_people'], $numberOfDays);
            $depositAmount = $pricing->deposit_amount;
        }

        RentalReservation::create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'contact_name' => $this->reservationData['contact_name'],
            'contact_email' => $this->reservationData['contact_email'],
            'contact_phone' => $this->reservationData['contact_phone'],
            'rental_purpose' => $this->reservationData['rental_purpose'],
            'number_of_people' => $this->reservationData['number_of_people'],
            'total_amount' => $baseTotal,
            'deposit_amount' => $depositAmount,
            'final_amount' => $baseTotal,
            'payment_status' => 'unpaid',
            'amount_paid' => 0,
            'status' => 'confirmed',
            'notes' => 'Created by admin: ' . Auth::user()->name . ($this->useCustomPricing ? ' (Custom pricing)' : ''),
        ]);

        $this->showCreateModal = false;
        $this->resetFormData();
        session()->flash('message', 'Rental reservation created successfully.');
    }

    public function updateReservation()
    {
        $this->authorize('update', $this->selectedReservation);
        
        $this->validate([
            'reservationData.contact_name' => 'required|string|max:255',
            'reservationData.contact_email' => 'required|email|max:255',
            'reservationData.contact_phone' => 'required|string|max:20',
            'reservationData.rental_purpose' => 'required|string|max:1000',
            'reservationData.number_of_people' => 'required|integer|min:1|max:100',
            'reservationData.start_date' => 'required|date',
            'reservationData.end_date' => 'required|date|after:start_date',
            'reservationData.status' => 'required|in:pending,confirmed,cancelled,completed',
            'reservationData.total_amount' => 'required|numeric|min:0',
            'reservationData.deposit_amount' => 'nullable|numeric|min:0',
            'reservationData.final_amount' => 'required|numeric|min:0',
        ]);

        // Check for blackout dates (if dates are being changed)
        $startDate = Carbon::parse($this->reservationData['start_date']);
        $endDate = Carbon::parse($this->reservationData['end_date']);
        
        if (RentalBlackoutDate::hasConflict($startDate, $endDate)) {
            $conflicts = RentalBlackoutDate::getConflicts($startDate, $endDate);
            $conflictMessages = $conflicts->map(function ($blackout) {
                return $blackout->reason . ' (' . $blackout->start_date->format('M j, Y') . ' - ' . $blackout->end_date->format('M j, Y') . ')';
            })->implode(', ');
            
            session()->flash('error', 'Cannot update reservation. The selected dates conflict with blackout period(s): ' . $conflictMessages);
            return;
        }

        $this->selectedReservation->update([
            'contact_name' => $this->reservationData['contact_name'],
            'contact_email' => $this->reservationData['contact_email'],
            'contact_phone' => $this->reservationData['contact_phone'],
            'rental_purpose' => $this->reservationData['rental_purpose'],
            'number_of_people' => $this->reservationData['number_of_people'],
            'start_date' => $this->reservationData['start_date'],
            'end_date' => $this->reservationData['end_date'],
            'status' => $this->reservationData['status'],
            'total_amount' => $this->reservationData['total_amount'],
            'deposit_amount' => $this->reservationData['deposit_amount'],
            'final_amount' => $this->reservationData['final_amount'],
            'notes' => $this->reservationData['notes'],
        ]);

        $this->showEditModal = false;
        $this->resetFormData();
        $this->selectedReservation = null;
        session()->flash('message', 'Rental reservation updated successfully.');
    }

    public function cancelReservation()
    {
        $this->authorize('cancel', $this->selectedReservation);
        
        if ($this->selectedReservation && $this->selectedReservation->canBeCancelled()) {
            $this->selectedReservation->update([
                'status' => 'cancelled',
                'notes' => $this->selectedReservation->notes . "\nCancelled by admin: " . Auth::user()->name . " on " . now()->format('Y-m-d H:i:s'),
            ]);

            $this->showCancelModal = false;
            $this->selectedReservation = null;
            session()->flash('message', 'Rental reservation cancelled successfully.');
        }
    }

    public function processRefund()
    {
        $this->authorize('refund', $this->selectedReservation);
        
        if ($this->selectedReservation) {
            // Here you would integrate with your payment processor (Stripe, etc.)
            // For now, we'll just mark it as refunded in notes
            $this->selectedReservation->update([
                'status' => 'cancelled',
                'notes' => $this->selectedReservation->notes . "\nRefund processed by admin: " . Auth::user()->name . " on " . now()->format('Y-m-d H:i:s'),
            ]);

            $this->showRefundModal = false;
            $this->selectedReservation = null;
            session()->flash('message', 'Refund processed successfully.');
        }
    }

    public function updatePricing()
    {
        $this->authorize('create', RentalPricing::class);
        
        $this->validate([
            'pricingData.price_per_person_per_day' => 'required|numeric|min:0',
            'pricingData.deposit_amount' => 'nullable|numeric|min:0',
            'pricingData.description' => 'required|string|max:255',
        ]);

        // Deactivate current pricing
        RentalPricing::where('is_active', true)->update(['is_active' => false]);

        // Create new pricing
        RentalPricing::create([
            'price_per_person_per_day' => $this->pricingData['price_per_person_per_day'],
            'deposit_amount' => $this->pricingData['deposit_amount'],
            'description' => $this->pricingData['description'],
            'is_active' => true,
        ]);

        $this->showPricingModal = false;
        session()->flash('message', 'Rental pricing updated successfully.');
    }

    public function createDiscountCode()
    {
        $this->authorize('create', DiscountCode::class);
        
        $this->validate([
            'discountData.code' => 'required|string|max:50|unique:discount_codes,code',
            'discountData.type' => 'required|in:rental,camper',
            'discountData.discount_type' => 'required|in:percentage,fixed',
            'discountData.discount_value' => 'required|numeric|min:0',
            'discountData.max_uses' => 'nullable|integer|min:1',
            'discountData.valid_from' => 'nullable|date',
            'discountData.valid_until' => 'nullable|date|after:valid_from',
            'discountData.description' => 'nullable|string|max:255',
        ]);

        DiscountCode::create([
            'code' => strtoupper($this->discountData['code']),
            'type' => $this->discountData['type'],
            'discount_type' => $this->discountData['discount_type'],
            'discount_value' => $this->discountData['discount_value'],
            'max_uses' => $this->discountData['max_uses'],
            'valid_from' => $this->discountData['valid_from'],
            'valid_until' => $this->discountData['valid_until'],
            'description' => $this->discountData['description'],
            'is_active' => true,
        ]);

        $this->showDiscountModal = false;
        $this->resetFormData();
        session()->flash('message', 'Discount code created successfully.');
    }

    public function deleteReservation()
    {
        $this->authorize('delete', $this->selectedReservation);
        
        if ($this->selectedReservation) {
            $this->selectedReservation->delete();
            $this->showDeleteModal = false;
            $this->selectedReservation = null;
            session()->flash('message', 'Rental reservation deleted successfully.');
        }
    }

    public function openPaymentModal($reservationId)
    {
        $this->selectedReservation = RentalReservation::findOrFail($reservationId);
        $this->paymentData = [
            'amount' => $this->selectedReservation->remaining_balance,
            'payment_method' => $this->selectedReservation->payment_method ?? 'check',
            'mark_as_paid' => false,
        ];
        $this->showPaymentModal = true;
    }

    public function recordPayment()
    {
        $this->validate([
            'paymentData.amount' => 'required|numeric|min:0.01',
            'paymentData.payment_method' => 'required|string|in:check,cash,credit_card,stripe,other',
        ]);

        if ($this->paymentData['mark_as_paid']) {
            // Mark as fully paid
            $this->selectedReservation->markAsPaid(
                $this->paymentData['payment_method'],
                $this->selectedReservation->final_amount
            );
        } else {
            // Record partial or full payment
            $this->selectedReservation->recordPayment(
                $this->paymentData['amount'],
                $this->paymentData['payment_method']
            );
        }

        $this->showPaymentModal = false;
        $this->selectedReservation = null;
        session()->flash('message', 'Payment recorded successfully.');
    }

    // Blackout Date Methods

    public function openBlackoutModal()
    {
        $this->authorize('create', RentalBlackoutDate::class);
        $this->resetBlackoutData();
        $this->showBlackoutModal = true;
    }

    public function openBlackoutEditModal($blackoutId)
    {
        $this->selectedBlackout = RentalBlackoutDate::findOrFail($blackoutId);
        $this->authorize('update', $this->selectedBlackout);
        
        $this->blackoutData = [
            'start_date' => $this->selectedBlackout->start_date->format('Y-m-d'),
            'end_date' => $this->selectedBlackout->end_date->format('Y-m-d'),
            'reason' => $this->selectedBlackout->reason,
            'notes' => $this->selectedBlackout->notes,
            'is_active' => $this->selectedBlackout->is_active,
        ];
        $this->showBlackoutEditModal = true;
    }

    public function openBlackoutDeleteModal($blackoutId)
    {
        $this->selectedBlackout = RentalBlackoutDate::findOrFail($blackoutId);
        $this->authorize('delete', $this->selectedBlackout);
        $this->showBlackoutDeleteModal = true;
    }

    public function createBlackoutDate()
    {
        $this->authorize('create', RentalBlackoutDate::class);
        
        $this->validate([
            'blackoutData.start_date' => 'required|date',
            'blackoutData.end_date' => 'required|date|after_or_equal:blackoutData.start_date',
            'blackoutData.reason' => 'required|string|max:255',
            'blackoutData.notes' => 'nullable|string|max:1000',
        ]);

        RentalBlackoutDate::create([
            'start_date' => $this->blackoutData['start_date'],
            'end_date' => $this->blackoutData['end_date'],
            'reason' => $this->blackoutData['reason'],
            'notes' => $this->blackoutData['notes'] ?? null,
            'is_active' => true,
        ]);

        $this->showBlackoutModal = false;
        $this->resetBlackoutData();
        session()->flash('message', 'Blackout date created successfully.');
    }

    public function updateBlackoutDate()
    {
        $this->authorize('update', $this->selectedBlackout);
        
        $this->validate([
            'blackoutData.start_date' => 'required|date',
            'blackoutData.end_date' => 'required|date|after_or_equal:blackoutData.start_date',
            'blackoutData.reason' => 'required|string|max:255',
            'blackoutData.notes' => 'nullable|string|max:1000',
            'blackoutData.is_active' => 'required|boolean',
        ]);

        $this->selectedBlackout->update([
            'start_date' => $this->blackoutData['start_date'],
            'end_date' => $this->blackoutData['end_date'],
            'reason' => $this->blackoutData['reason'],
            'notes' => $this->blackoutData['notes'] ?? null,
            'is_active' => $this->blackoutData['is_active'],
        ]);

        $this->showBlackoutEditModal = false;
        $this->selectedBlackout = null;
        $this->resetBlackoutData();
        session()->flash('message', 'Blackout date updated successfully.');
    }

    public function deleteBlackoutDate()
    {
        $this->authorize('delete', $this->selectedBlackout);
        
        if ($this->selectedBlackout) {
            $this->selectedBlackout->delete();
            $this->showBlackoutDeleteModal = false;
            $this->selectedBlackout = null;
            session()->flash('message', 'Blackout date deleted successfully.');
        }
    }

    public function resetBlackoutData()
    {
        $this->blackoutData = [
            'start_date' => '',
            'end_date' => '',
            'reason' => '',
            'notes' => '',
            'is_active' => true,
        ];
    }

    public function resetFormData()
    {
        $this->reservationData = [
            'contact_name' => '',
            'contact_email' => '',
            'contact_phone' => '',
            'rental_purpose' => '',
            'number_of_people' => '',
            'start_date' => '',
            'end_date' => '',
            'status' => 'pending',
            'custom_total' => '',
            'custom_deposit' => '',
            'total_amount' => '',
            'deposit_amount' => '',
            'final_amount' => '',
            'notes' => '',
        ];

        $this->pricingData = [
            'price_per_person_per_day' => 0,
            'deposit_amount' => 0,
            'description' => '',
        ];

        $this->discountData = [
            'code' => '',
            'type' => 'rental',
            'discount_type' => 'percentage',
            'discount_value' => 0,
            'max_uses' => '',
            'valid_from' => '',
            'valid_until' => '',
            'description' => '',
        ];

        $this->paymentData = [
            'amount' => 0,
            'payment_method' => 'check',
            'mark_as_paid' => false,
        ];

        $this->useCustomPricing = false;
        $this->calculatedTotal = 0;
    }

    public function render()
    {
        $query = RentalReservation::query();

        // Apply search filter
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('contact_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('contact_email', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('rental_purpose', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply payment method filter
        if ($this->paymentMethodFilter) {
            if ($this->paymentMethodFilter === 'mail_check') {
                $query->where('notes', 'like', '%mail check%');
            } else {
                $query->whereNotNull('stripe_payment_intent_id');
            }
        }

        // Apply date filter
        if ($this->dateFilter) {
            $now = now();
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('start_date', $now->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('start_date', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereBetween('start_date', [$now->startOfMonth(), $now->endOfMonth()]);
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
                case 'past':
                    $query->where('end_date', '<', $now);
                    break;
            }
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get analytics data
        $analytics = [
            'total_reservations' => RentalReservation::count(),
            'pending_reservations' => RentalReservation::where('status', 'pending')->count(),
            'confirmed_reservations' => RentalReservation::where('status', 'confirmed')->count(),
            'cancelled_reservations' => RentalReservation::where('status', 'cancelled')->count(),
            'completed_reservations' => RentalReservation::where('status', 'completed')->count(),
            'total_revenue' => RentalReservation::where('status', 'confirmed')->sum('final_amount'),
            'pending_revenue' => RentalReservation::where('status', 'pending')->sum('final_amount'),
            'monthly_revenue' => RentalReservation::where('status', 'confirmed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('final_amount'),
            'yearly_revenue' => RentalReservation::where('status', 'confirmed')
                ->whereYear('created_at', now()->year)
                ->sum('final_amount'),
            'average_reservation_value' => RentalReservation::where('status', 'confirmed')->avg('final_amount'),
            'upcoming_reservations' => RentalReservation::where('status', 'confirmed')
                ->where('start_date', '>', now())
                ->count(),
            'recent_reservations' => RentalReservation::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $discountCodes = DiscountCode::where('type', 'rental')->orderBy('created_at', 'desc')->get();
        $blackoutDates = RentalBlackoutDate::orderBy('start_date', 'desc')->get();

        return view('livewire.admin.rental-management', [
            'reservations' => $reservations,
            'analytics' => $analytics,
            'discountCodes' => $discountCodes,
            'blackoutDates' => $blackoutDates,
        ]);
    }
}
