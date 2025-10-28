<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RentalReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'contact_name',
        'contact_email',
        'contact_phone',
        'rental_purpose',
        'number_of_people',
        'total_amount',
        'deposit_amount',
        'discount_code_id',
        'final_amount',
        'amount_paid',
        'payment_status',
        'payment_method',
        'payment_date',
        'stripe_payment_intent_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the discount code for this reservation.
     */
    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    /**
     * Get the number of days for this reservation.
     */
    public function getDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if the reservation is active (not cancelled or completed).
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Check if the reservation can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->isActive() && $this->start_date->isFuture();
    }

    /**
     * Scope for active reservations.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    /**
     * Scope for reservations in a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }

    /**
     * Check if the reservation is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'paid' || 
               ($this->amount_paid && $this->amount_paid >= $this->final_amount);
    }

    /**
     * Check if the reservation is partially paid.
     */
    public function isPartiallyPaid(): bool
    {
        return $this->amount_paid > 0 && $this->amount_paid < $this->final_amount;
    }

    /**
     * Get the remaining balance.
     */
    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->final_amount - ($this->amount_paid ?? 0));
    }

    /**
     * Mark reservation as paid.
     */
    public function markAsPaid(string $paymentMethod = null, float $amount = null): void
    {
        $this->update([
            'payment_status' => 'paid',
            'amount_paid' => $amount ?? $this->final_amount,
            'payment_method' => $paymentMethod ?? $this->payment_method,
            'payment_date' => now(),
        ]);
    }

    /**
     * Record a partial payment.
     */
    public function recordPayment(float $amount, string $paymentMethod = null): void
    {
        $newAmountPaid = ($this->amount_paid ?? 0) + $amount;
        $paymentStatus = $newAmountPaid >= $this->final_amount ? 'paid' : 'partial';

        $this->update([
            'amount_paid' => $newAmountPaid,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod ?? $this->payment_method,
            'payment_date' => now(),
        ]);
    }
}
