<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrollment_can_be_marked_as_pay_at_checkin()
    {
        // Create a simple user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a simple enrollment manually
        $enrollment = Enrollment::create([
            'camp_instance_id' => 1,
            'camper_id' => 1,
            'status' => 'pending',
            'balance_cents' => 10000, // $100
            'amount_paid_cents' => 0,
            'forms_complete' => true,
        ]);

        $paymentService = app(PaymentService::class);
        $paymentService->markAsPayAtCheckin($enrollment);

        $this->assertEquals('registered_awaiting_payment', $enrollment->fresh()->status);
    }

    public function test_enrollment_can_be_marked_as_fully_paid()
    {
        // Create a simple user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a simple enrollment manually
        $enrollment = Enrollment::create([
            'camp_instance_id' => 1,
            'camper_id' => 1,
            'status' => 'registered_awaiting_payment',
            'balance_cents' => 10000, // $100
            'amount_paid_cents' => 0,
            'forms_complete' => true,
        ]);

        $paymentService = app(PaymentService::class);
        $paymentService->processManualPayment($enrollment, $user, 100.00, 'cash');

        $this->assertEquals('registered_fully_paid', $enrollment->fresh()->status);
        $this->assertEquals(10000, $enrollment->fresh()->amount_paid_cents);
    }

    public function test_payment_service_can_process_manual_payment()
    {
        // Create a simple user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a simple enrollment manually
        $enrollment = Enrollment::create([
            'camp_instance_id' => 1,
            'camper_id' => 1,
            'status' => 'pending',
            'balance_cents' => 15000, // $150
            'amount_paid_cents' => 0,
            'forms_complete' => true,
        ]);

        $paymentService = app(PaymentService::class);
        $payment = $paymentService->processManualPayment($enrollment, $user, 150.00, 'check', 'CHECK123');

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals('succeeded', $payment->status);
        $this->assertEquals(15000, $payment->amount_cents);
        $this->assertEquals('check', $payment->method);
        $this->assertEquals('CHECK123', $payment->reference);
        $this->assertEquals('registered_fully_paid', $enrollment->fresh()->status);
    }

    public function test_enrollment_status_methods_work_correctly()
    {
        $enrollment = Enrollment::create([
            'camp_instance_id' => 1,
            'camper_id' => 1,
            'status' => 'registered_awaiting_payment',
            'balance_cents' => 10000,
            'amount_paid_cents' => 0,
            'forms_complete' => true,
        ]);

        $this->assertTrue($enrollment->isRegisteredAwaitingPayment());
        $this->assertTrue($enrollment->isRegistered());
        $this->assertFalse($enrollment->isRegisteredFullyPaid());

        $enrollment->update(['status' => 'registered_fully_paid']);

        $this->assertTrue($enrollment->isRegisteredFullyPaid());
        $this->assertTrue($enrollment->isRegistered());
        $this->assertFalse($enrollment->isRegisteredAwaitingPayment());
    }

    public function test_payment_model_has_correct_attributes()
    {
        $payment = Payment::create([
            'enrollment_id' => 1,
            'amount_cents' => 5000,
            'method' => 'credit_card',
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);

        $this->assertEquals(50.00, $payment->amount);
        $this->assertEquals('$50.00', $payment->formatted_amount);
        $this->assertTrue($payment->isConfirmed());
        $this->assertFalse($payment->isPending());
        $this->assertFalse($payment->hasFailed());
    }
}
