<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;

class PaymentService
{
    /**
     * Process a payment for an enrollment using Stripe.
     */
    public function processStripePayment(Enrollment $enrollment, User $user, string $paymentMethodId, array $metadata = []): Payment
    {
        return DB::transaction(function () use ($enrollment, $user, $paymentMethodId, $metadata) {
            // Create payment record
            $payment = Payment::create([
                'enrollment_id' => $enrollment->id,
                'amount_cents' => $enrollment->outstanding_balance_cents,
                'method' => 'credit_card',
                'status' => 'pending',
                'stripe_metadata' => $metadata,
                'processed_by_user_id' => $user->id,
            ]);

            try {
                // Create Stripe payment intent
                $paymentIntent = $user->createSetupIntent([
                    'payment_method' => $paymentMethodId,
                    'amount' => $enrollment->outstanding_balance_cents,
                    'currency' => 'usd',
                    'metadata' => array_merge($metadata, [
                        'enrollment_id' => $enrollment->id,
                        'payment_id' => $payment->id,
                        'camper_name' => $enrollment->camper->full_name,
                        'camp_name' => $enrollment->campInstance->camp->name,
                    ]),
                ]);

                // Update payment with Stripe payment intent ID
                $payment->update([
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'processing',
                ]);

                // Confirm the payment intent
                $confirmedIntent = $user->confirmSetupIntent($paymentIntent->id, [
                    'payment_method' => $paymentMethodId,
                ]);

                if ($confirmedIntent->status === 'succeeded') {
                    // Payment successful
                    $payment->update([
                        'status' => 'succeeded',
                        'stripe_charge_id' => $confirmedIntent->latest_charge,
                        'paid_at' => now(),
                        'processed_at' => now(),
                    ]);

                    // Update enrollment balance
                    $enrollment->updateBalanceAfterPayment($enrollment->outstanding_balance_cents);

                    // Mark enrollment as fully paid if balance is zero
                    if ($enrollment->isFullyPaid()) {
                        $enrollment->markAsRegisteredFullyPaid();
                    }

                    Log::info('Payment processed successfully', [
                        'payment_id' => $payment->id,
                        'enrollment_id' => $enrollment->id,
                        'amount' => $payment->amount,
                        'user_id' => $user->id,
                    ]);
                } else {
                    // Payment failed
                    $payment->update([
                        'status' => 'failed',
                        'processed_at' => now(),
                    ]);

                    Log::error('Payment failed', [
                        'payment_id' => $payment->id,
                        'enrollment_id' => $enrollment->id,
                        'stripe_status' => $confirmedIntent->status,
                        'user_id' => $user->id,
                    ]);
                }

                return $payment;

            } catch (CardException $e) {
                // Handle card errors
                $payment->update([
                    'status' => 'failed',
                    'processed_at' => now(),
                    'notes' => 'Card error: ' . $e->getMessage(),
                ]);

                Log::error('Card payment failed', [
                    'payment_id' => $payment->id,
                    'enrollment_id' => $enrollment->id,
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                ]);

                throw $e;

            } catch (InvalidRequestException $e) {
                // Handle invalid request errors
                $payment->update([
                    'status' => 'failed',
                    'processed_at' => now(),
                    'notes' => 'Invalid request: ' . $e->getMessage(),
                ]);

                Log::error('Invalid payment request', [
                    'payment_id' => $payment->id,
                    'enrollment_id' => $enrollment->id,
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                ]);

                throw $e;

            } catch (\Exception $e) {
                // Handle other errors
                $payment->update([
                    'status' => 'failed',
                    'processed_at' => now(),
                    'notes' => 'Payment error: ' . $e->getMessage(),
                ]);

                Log::error('Payment processing error', [
                    'payment_id' => $payment->id,
                    'enrollment_id' => $enrollment->id,
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                ]);

                throw $e;
            }
        });
    }

    /**
     * Process a manual payment (cash, check, etc.).
     */
    public function processManualPayment(Enrollment $enrollment, User $user, float $amount, string $method, string $reference = null, string $notes = null): Payment
    {
        return DB::transaction(function () use ($enrollment, $user, $amount, $method, $reference, $notes) {
            $amountCents = (int) ($amount * 100);

            // Create payment record
            $payment = Payment::create([
                'enrollment_id' => $enrollment->id,
                'amount_cents' => $amountCents,
                'method' => $method,
                'reference' => $reference,
                'status' => 'succeeded',
                'paid_at' => now(),
                'processed_at' => now(),
                'notes' => $notes,
                'processed_by_user_id' => $user->id,
            ]);

            // Update enrollment balance
            $enrollment->updateBalanceAfterPayment($amountCents);

            // Mark enrollment as fully paid if balance is zero
            if ($enrollment->isFullyPaid()) {
                $enrollment->markAsRegisteredFullyPaid();
            }

            Log::info('Manual payment processed', [
                'payment_id' => $payment->id,
                'enrollment_id' => $enrollment->id,
                'amount' => $payment->amount,
                'method' => $method,
                'user_id' => $user->id,
            ]);

            return $payment;
        });
    }

    /**
     * Mark enrollment as "Pay at Check-in".
     */
    public function markAsPayAtCheckin(Enrollment $enrollment): void
    {
        $enrollment->markAsRegisteredAwaitingPayment();

        Log::info('Enrollment marked as pay at check-in', [
            'enrollment_id' => $enrollment->id,
            'camper_name' => $enrollment->camper->full_name,
            'camp_name' => $enrollment->campInstance->camp->name,
        ]);
    }

    /**
     * Get payment methods for a user.
     */
    public function getPaymentMethods(User $user): array
    {
        try {
            $paymentMethods = $user->paymentMethods();
            return $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'type' => $method->type,
                    'card' => $method->card ? [
                        'brand' => $method->card->brand,
                        'last4' => $method->card->last4,
                        'exp_month' => $method->card->exp_month,
                        'exp_year' => $method->card->exp_year,
                    ] : null,
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error retrieving payment methods', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Add a payment method to a user.
     */
    public function addPaymentMethod(User $user, string $paymentMethodId): bool
    {
        try {
            $user->addPaymentMethod($paymentMethodId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error adding payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Remove a payment method from a user.
     */
    public function removePaymentMethod(User $user, string $paymentMethodId): bool
    {
        try {
            $user->removePaymentMethod($paymentMethodId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error removing payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
