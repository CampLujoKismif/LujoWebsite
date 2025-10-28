<?php

namespace App\Http\Controllers;

use App\Models\RentalReservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhook events for rental payments.
     */
    public function handleStripeWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (!$webhookSecret) {
            Log::error('Stripe webhook secret not configured');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Invalid Stripe webhook payload', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Invalid Stripe webhook signature', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        try {
            $this->handleEvent($event);
        } catch (\Exception $e) {
            Log::error('Error handling Stripe webhook event', [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Return 200 to acknowledge receipt even if processing failed
            // Stripe will retry failed webhooks
        }

        return response()->json(['received' => true]);
    }

    /**
     * Process the webhook event based on type.
     */
    protected function handleEvent($event): void
    {
        Log::info('Stripe webhook received', [
            'event_type' => $event->type,
            'event_id' => $event->id,
        ]);

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;

            case 'payment_intent.canceled':
                $this->handlePaymentIntentCanceled($event->data->object);
                break;

            case 'charge.refunded':
                $this->handleChargeRefunded($event->data->object);
                break;

            case 'charge.dispute.created':
                $this->handleDisputeCreated($event->data->object);
                break;

            default:
                Log::info('Unhandled Stripe webhook event type', [
                    'event_type' => $event->type,
                ]);
        }
    }

    /**
     * Handle successful payment intent.
     */
    protected function handlePaymentIntentSucceeded($paymentIntent): void
    {
        $reservationId = $paymentIntent->metadata->reservation_id ?? null;

        if (!$reservationId) {
            Log::warning('Payment intent succeeded but no reservation ID in metadata', [
                'payment_intent_id' => $paymentIntent->id,
            ]);
            return;
        }

        $reservation = RentalReservation::find($reservationId);

        if (!$reservation) {
            Log::error('Reservation not found for payment intent', [
                'payment_intent_id' => $paymentIntent->id,
                'reservation_id' => $reservationId,
            ]);
            return;
        }

        // Update reservation with payment information
        $reservation->update([
            'stripe_payment_intent_id' => $paymentIntent->id,
            'payment_status' => 'paid',
            'payment_method' => 'credit_card',
            'payment_date' => now(),
            'amount_paid' => $paymentIntent->amount / 100,
            'status' => 'confirmed',
        ]);

        Log::info('Rental payment confirmed via webhook', [
            'reservation_id' => $reservation->id,
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount / 100,
        ]);

        // TODO: Send confirmation email to customer
        // TODO: Send notification to admin
    }

    /**
     * Handle failed payment intent.
     */
    protected function handlePaymentIntentFailed($paymentIntent): void
    {
        $reservationId = $paymentIntent->metadata->reservation_id ?? null;

        if (!$reservationId) {
            return;
        }

        $reservation = RentalReservation::find($reservationId);

        if (!$reservation) {
            return;
        }

        // Update reservation status
        $reservation->update([
            'payment_status' => 'failed',
            'notes' => ($reservation->notes ?? '') . "\nPayment failed: " . ($paymentIntent->last_payment_error->message ?? 'Unknown error'),
        ]);

        Log::warning('Rental payment failed', [
            'reservation_id' => $reservation->id,
            'payment_intent_id' => $paymentIntent->id,
            'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
        ]);

        // TODO: Send payment failure notification to customer
        // TODO: Send notification to admin
    }

    /**
     * Handle canceled payment intent.
     */
    protected function handlePaymentIntentCanceled($paymentIntent): void
    {
        $reservationId = $paymentIntent->metadata->reservation_id ?? null;

        if (!$reservationId) {
            return;
        }

        $reservation = RentalReservation::find($reservationId);

        if (!$reservation) {
            return;
        }

        // Update reservation status
        $reservation->update([
            'payment_status' => 'canceled',
            'notes' => ($reservation->notes ?? '') . "\nPayment canceled by customer or system.",
        ]);

        Log::info('Rental payment canceled', [
            'reservation_id' => $reservation->id,
            'payment_intent_id' => $paymentIntent->id,
        ]);

        // TODO: Send cancellation notification to customer
    }

    /**
     * Handle charge refund.
     */
    protected function handleChargeRefunded($charge): void
    {
        // Find reservation by payment intent ID
        $reservation = RentalReservation::where('stripe_payment_intent_id', $charge->payment_intent)
            ->first();

        if (!$reservation) {
            Log::warning('Reservation not found for refunded charge', [
                'charge_id' => $charge->id,
                'payment_intent_id' => $charge->payment_intent,
            ]);
            return;
        }

        $refundAmount = $charge->amount_refunded / 100;
        $isFullRefund = $charge->refunded;

        // Update reservation
        $newAmountPaid = max(0, $reservation->amount_paid - $refundAmount);
        $reservation->update([
            'amount_paid' => $newAmountPaid,
            'payment_status' => $isFullRefund ? 'refunded' : 'partial_refund',
            'notes' => ($reservation->notes ?? '') . "\nRefund processed: $" . number_format($refundAmount, 2),
        ]);

        Log::info('Rental payment refunded', [
            'reservation_id' => $reservation->id,
            'charge_id' => $charge->id,
            'refund_amount' => $refundAmount,
            'is_full_refund' => $isFullRefund,
        ]);

        // TODO: Send refund confirmation email to customer
        // TODO: Send notification to admin
    }

    /**
     * Handle dispute created.
     */
    protected function handleDisputeCreated($dispute): void
    {
        // Find reservation by payment intent ID or charge ID
        $reservation = RentalReservation::where('stripe_payment_intent_id', $dispute->payment_intent)
            ->first();

        if (!$reservation) {
            Log::warning('Reservation not found for disputed charge', [
                'dispute_id' => $dispute->id,
                'payment_intent_id' => $dispute->payment_intent,
            ]);
            return;
        }

        // Update reservation
        $reservation->update([
            'payment_status' => 'disputed',
            'notes' => ($reservation->notes ?? '') . "\nPayment disputed: " . ($dispute->reason ?? 'Unknown reason'),
        ]);

        Log::error('Rental payment disputed', [
            'reservation_id' => $reservation->id,
            'dispute_id' => $dispute->id,
            'reason' => $dispute->reason ?? 'Unknown',
            'amount' => $dispute->amount / 100,
        ]);

        // TODO: Send urgent notification to admin
        // TODO: Log dispute details for follow-up
    }
}

