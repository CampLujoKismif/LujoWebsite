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
}
