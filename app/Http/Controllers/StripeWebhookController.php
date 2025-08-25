<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Stripe\Event;

class StripeWebhookController extends CashierController
{
    /**
     * Handle payment intent succeeded.
     */
    protected function handlePaymentIntentSucceeded(Event $event)
    {
        $paymentIntent = $event->data->object;
        
        // Find the payment record
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'succeeded',
                'stripe_charge_id' => $paymentIntent->latest_charge,
                'paid_at' => now(),
                'processed_at' => now(),
            ]);

            // Update enrollment balance
            $enrollment = $payment->enrollment;
            $enrollment->updateBalanceAfterPayment($payment->amount_cents);

            // Mark enrollment as fully paid if balance is zero
            if ($enrollment->isFullyPaid()) {
                $enrollment->markAsRegisteredFullyPaid();
            }

            Log::info('Payment succeeded via webhook', [
                'payment_id' => $payment->id,
                'enrollment_id' => $enrollment->id,
                'amount' => $payment->amount,
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle payment intent payment failed.
     */
    protected function handlePaymentIntentPaymentFailed(Event $event)
    {
        $paymentIntent = $event->data->object;
        
        // Find the payment record
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'processed_at' => now(),
                'notes' => 'Payment failed via webhook: ' . ($paymentIntent->last_payment_error->message ?? 'Unknown error'),
            ]);

            Log::error('Payment failed via webhook', [
                'payment_id' => $payment->id,
                'enrollment_id' => $payment->enrollment_id,
                'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle payment intent canceled.
     */
    protected function handlePaymentIntentCanceled(Event $event)
    {
        $paymentIntent = $event->data->object;
        
        // Find the payment record
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'cancelled',
                'processed_at' => now(),
                'notes' => 'Payment cancelled via webhook',
            ]);

            Log::info('Payment cancelled via webhook', [
                'payment_id' => $payment->id,
                'enrollment_id' => $payment->enrollment_id,
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle charge succeeded.
     */
    protected function handleChargeSucceeded(Event $event)
    {
        $charge = $event->data->object;
        
        // Find the payment record by charge ID
        $payment = Payment::where('stripe_charge_id', $charge->id)->first();
        
        if ($payment && $payment->status !== 'succeeded') {
            $payment->update([
                'status' => 'succeeded',
                'paid_at' => now(),
                'processed_at' => now(),
            ]);

            // Update enrollment balance
            $enrollment = $payment->enrollment;
            $enrollment->updateBalanceAfterPayment($payment->amount_cents);

            // Mark enrollment as fully paid if balance is zero
            if ($enrollment->isFullyPaid()) {
                $enrollment->markAsRegisteredFullyPaid();
            }

            Log::info('Charge succeeded via webhook', [
                'payment_id' => $payment->id,
                'enrollment_id' => $enrollment->id,
                'amount' => $payment->amount,
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle charge failed.
     */
    protected function handleChargeFailed(Event $event)
    {
        $charge = $event->data->object;
        
        // Find the payment record by charge ID
        $payment = Payment::where('stripe_charge_id', $charge->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'processed_at' => now(),
                'notes' => 'Charge failed via webhook: ' . ($charge->failure_message ?? 'Unknown error'),
            ]);

            Log::error('Charge failed via webhook', [
                'payment_id' => $payment->id,
                'enrollment_id' => $payment->enrollment_id,
                'error' => $charge->failure_message ?? 'Unknown error',
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle charge refunded.
     */
    protected function handleChargeRefunded(Event $event)
    {
        $charge = $event->data->object;
        
        // Find the payment record by charge ID
        $payment = Payment::where('stripe_charge_id', $charge->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'cancelled',
                'processed_at' => now(),
                'notes' => 'Payment refunded via webhook',
            ]);

            // Recalculate enrollment balance (this would need to be implemented based on your business logic)
            // For now, we'll just log the refund
            Log::info('Payment refunded via webhook', [
                'payment_id' => $payment->id,
                'enrollment_id' => $payment->enrollment_id,
                'amount' => $payment->amount,
            ]);
        }

        return $this->successMethod();
    }
}
