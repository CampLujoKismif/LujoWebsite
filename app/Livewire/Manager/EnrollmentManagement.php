<?php

namespace App\Livewire\Manager;

use App\Models\Enrollment;
use App\Models\CampInstance;
use App\Models\Camper;
use App\Models\Family;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class EnrollmentManagement extends Component
{
    use WithPagination;

    public $enrollments;
    public $campInstances;
    public $selectedEnrollment;
    public $selectedCampInstance;
    public $showDetailsModal = false;
    public $showStatusModal = false;
    public $showPaymentModal = false;
    
    // Filters
    public $statusFilter = '';
    public $searchTerm = '';
    public $balanceFilter = '';
    
    // Status update
    public $newStatus = '';
    public $statusNotes = '';
    
    // Payment
    public $paymentAmount = '';
    public $paymentMethod = 'cash';
    public $paymentReference = '';
    public $paymentNotes = '';

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'searchTerm' => ['except' => ''],
        'balanceFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        
        // Get camp instances assigned to the manager
        $this->campInstances = CampInstance::whereHas('camp', function ($query) use ($user) {
            $query->whereHas('managers', function ($managerQuery) use ($user) {
                $managerQuery->where('user_id', $user->id);
            });
        })
        ->with('camp')
        ->where('is_active', true)
        ->orderBy('start_date')
        ->get();

        // Set default selected camp instance to the first one
        if ($this->campInstances->isNotEmpty() && !$this->selectedCampInstance) {
            $this->selectedCampInstance = $this->campInstances->first()->id;
        }
    }

    public function updatedSelectedCampInstance()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedBalanceFilter()
    {
        $this->resetPage();
    }

    public function openDetailsModal(Enrollment $enrollment)
    {
        $this->selectedEnrollment = $enrollment->load(['camper.family', 'campInstance.camp', 'payments', 'formResponses.formTemplate']);
        $this->showDetailsModal = true;
    }

    public function openStatusModal(Enrollment $enrollment)
    {
        $this->selectedEnrollment = $enrollment;
        $this->newStatus = $enrollment->status;
        $this->statusNotes = '';
        $this->showStatusModal = true;
    }

    public function openPaymentModal(Enrollment $enrollment)
    {
        $this->selectedEnrollment = $enrollment;
        $this->paymentAmount = '';
        $this->paymentMethod = 'cash';
        $this->paymentReference = '';
        $this->paymentNotes = '';
        $this->showPaymentModal = true;
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:pending,confirmed,waitlisted,cancelled',
            'statusNotes' => 'nullable|string|max:500',
        ]);

        $this->selectedEnrollment->update([
            'status' => $this->newStatus,
            'notes' => $this->statusNotes ? ($this->selectedEnrollment->notes ? $this->selectedEnrollment->notes . "\n\n" . $this->statusNotes : $this->statusNotes) : $this->selectedEnrollment->notes,
        ]);

        $this->showStatusModal = false;
        $this->dispatch('enrollment-status-updated');
    }

    public function recordPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0.01',
            'paymentMethod' => 'required|in:cash,check,credit_card,online',
            'paymentReference' => 'nullable|string|max:255',
            'paymentNotes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () {
            $amountCents = (int) ($this->paymentAmount * 100);
            
            $this->selectedEnrollment->payments()->create([
                'amount_cents' => $amountCents,
                'method' => $this->paymentMethod,
                'reference' => $this->paymentReference,
                'paid_at' => now(),
                'notes' => $this->paymentNotes,
                'processed_by_user_id' => auth()->id(),
            ]);

            // Update enrollment balance
            $this->selectedEnrollment->increment('amount_paid_cents', $amountCents);
        });

        $this->showPaymentModal = false;
        $this->dispatch('payment-recorded');
    }

    public function getEnrollmentsProperty()
    {
        if (!$this->selectedCampInstance) {
            return collect()->paginate(15);
        }

        $query = Enrollment::with(['camper.family', 'campInstance.camp'])
            ->where('camp_instance_id', $this->selectedCampInstance)
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->whereHas('camper', function ($camperQuery) {
                    $camperQuery->where('first_name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                })
                ->orWhereHas('camper.family', function ($familyQuery) {
                    $familyQuery->where('name', 'like', '%' . $this->searchTerm . '%');
                });
            });
        }

        if ($this->balanceFilter) {
            switch ($this->balanceFilter) {
                case 'has_balance':
                    $query->whereRaw('balance_cents > amount_paid_cents');
                    break;
                case 'paid_in_full':
                    $query->whereRaw('balance_cents <= amount_paid_cents');
                    break;
                case 'no_payment':
                    $query->where('amount_paid_cents', 0);
                    break;
            }
        }

        return $query->paginate(15);
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'confirmed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'waitlisted' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getBalanceStatus($enrollment)
    {
        if ($enrollment->amount_paid_cents >= $enrollment->balance_cents) {
            return 'Paid in Full';
        } elseif ($enrollment->amount_paid_cents > 0) {
            return 'Partial Payment';
        } else {
            return 'No Payment';
        }
    }

    public function getBalanceStatusColor($enrollment)
    {
        if ($enrollment->amount_paid_cents >= $enrollment->balance_cents) {
            return 'text-green-600';
        } elseif ($enrollment->amount_paid_cents > 0) {
            return 'text-yellow-600';
        } else {
            return 'text-red-600';
        }
    }

    public function getSessionStats()
    {
        if (!$this->selectedCampInstance) {
            return [
                'total' => 0,
                'confirmed' => 0,
                'pending' => 0,
                'waitlisted' => 0,
                'cancelled' => 0,
                'completed' => 0,
            ];
        }

        $enrollments = Enrollment::where('camp_instance_id', $this->selectedCampInstance);
        
        return [
            'total' => $enrollments->count(),
            'confirmed' => $enrollments->where('status', 'confirmed')->count(),
            'pending' => $enrollments->where('status', 'pending')->count(),
            'waitlisted' => $enrollments->where('status', 'waitlisted')->count(),
            'cancelled' => $enrollments->where('status', 'cancelled')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.manager.enrollment-management')->layout('components.layouts.app');
    }
}
