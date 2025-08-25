<?php

namespace App\Livewire\ParentPortal;

use App\Models\Enrollment;
use App\Services\PaymentService;
use Livewire\Component;
use Livewire\Attributes\Layout;

class PaymentProcessing extends Component
{
    public $enrollments = [];
    public $selectedEnrollments = [];
    public $paymentMethod = 'stripe';
    public $paymentMethodId = '';
    public $payAtCheckin = false;
    public $showPaymentForm = false;
    public $processing = false;
    public $error = '';
    public $success = '';

    protected $listeners = ['enrollmentSelected' => 'handleEnrollmentSelection'];

    public function mount()
    {
        $this->loadEnrollments();
    }

    public function loadEnrollments()
    {
        $user = auth()->user();
        
        // Get enrollments that need payment
        $this->enrollments = Enrollment::whereIn('camper_id', $user->accessibleCampers()->pluck('id'))
            ->where('forms_complete', true)
            ->whereIn('status', ['pending', 'registered_awaiting_payment'])
            ->whereRaw('balance_cents > amount_paid_cents')
            ->with(['camper.family', 'campInstance.camp'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function handleEnrollmentSelection($enrollmentId)
    {
        if (in_array($enrollmentId, $this->selectedEnrollments)) {
            $this->selectedEnrollments = array_diff($this->selectedEnrollments, [$enrollmentId]);
        } else {
            $this->selectedEnrollments[] = $enrollmentId;
        }
    }

    public function getSelectedEnrollmentsProperty()
    {
        return $this->enrollments->whereIn('id', $this->selectedEnrollments);
    }

    public function getTotalAmountProperty()
    {
        return $this->selectedEnrollments->sum('outstanding_balance');
    }

    public function getTotalAmountCentsProperty()
    {
        return $this->selectedEnrollments->sum('outstanding_balance_cents');
    }

    public function proceedToPayment()
    {
        if (empty($this->selectedEnrollments)) {
            $this->error = 'Please select at least one enrollment to pay for.';
            return;
        }

        if ($this->payAtCheckin) {
            $this->markAsPayAtCheckin();
        } else {
            $this->showPaymentForm = true;
        }
    }

    public function markAsPayAtCheckin()
    {
        $this->processing = true;
        $this->error = '';
        $this->success = '';

        try {
            $paymentService = app(PaymentService::class);
            
            foreach ($this->selectedEnrollments as $enrollment) {
                $paymentService->markAsPayAtCheckin($enrollment);
            }

            $this->success = 'Enrollments marked as "Pay at Check-in" successfully!';
            $this->selectedEnrollments = [];
            $this->showPaymentForm = false;
            $this->loadEnrollments();

        } catch (\Exception $e) {
            $this->error = 'Error marking enrollments for pay at check-in: ' . $e->getMessage();
        } finally {
            $this->processing = false;
        }
    }

    public function processPayment()
    {
        $this->validate([
            'paymentMethodId' => 'required_if:paymentMethod,stripe',
        ], [
            'paymentMethodId.required_if' => 'Please select a payment method.',
        ]);

        $this->processing = true;
        $this->error = '';
        $this->success = '';

        try {
            $paymentService = app(PaymentService::class);
            $user = auth()->user();

            foreach ($this->selectedEnrollments as $enrollment) {
                if ($this->paymentMethod === 'stripe') {
                    $paymentService->processStripePayment(
                        $enrollment,
                        $user,
                        $this->paymentMethodId,
                        [
                            'camp_registration' => true,
                            'camper_id' => $enrollment->camper_id,
                            'camp_instance_id' => $enrollment->camp_instance_id,
                        ]
                    );
                }
            }

            $this->success = 'Payment processed successfully!';
            $this->selectedEnrollments = [];
            $this->showPaymentForm = false;
            $this->loadEnrollments();

        } catch (\Exception $e) {
            $this->error = 'Payment failed: ' . $e->getMessage();
        } finally {
            $this->processing = false;
        }
    }

    public function cancelPayment()
    {
        $this->showPaymentForm = false;
        $this->paymentMethodId = '';
        $this->error = '';
    }

    public function render()
    {
        return view('livewire.parent-portal.payment-processing')->layout('components.layouts.app');
    }
}
