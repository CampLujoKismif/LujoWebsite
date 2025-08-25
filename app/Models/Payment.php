<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'amount_cents',
        'method',
        'reference',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'status',
        'stripe_metadata',
        'paid_at',
        'processed_at',
        'notes',
        'processed_by_user_id',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'stripe_metadata' => 'array',
        'paid_at' => 'datetime',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the enrollment for this payment.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Get the camper for this payment.
     */
    public function camper(): BelongsTo
    {
        return $this->enrollment->camper();
    }

    /**
     * Get the family for this payment.
     */
    public function family(): BelongsTo
    {
        return $this->enrollment->family();
    }

    /**
     * Get the camp instance for this payment.
     */
    public function campInstance(): BelongsTo
    {
        return $this->enrollment->campInstance();
    }

    /**
     * Get the user who processed this payment.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    /**
     * Get the amount in dollars.
     */
    public function getAmountAttribute(): float
    {
        return $this->amount_cents / 100;
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Check if the payment is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'succeeded' && $this->paid_at !== null;
    }

    /**
     * Check if the payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the payment is processing.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if the payment failed.
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the payment was cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if this is a Stripe payment.
     */
    public function isStripePayment(): bool
    {
        return $this->stripe_payment_intent_id !== null;
    }

    /**
     * Get the payment status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'succeeded' => 'text-green-600',
            'processing' => 'text-yellow-600',
            'pending' => 'text-blue-600',
            'failed' => 'text-red-600',
            'cancelled' => 'text-gray-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Get the payment method display name.
     */
    public function getMethodDisplayAttribute(): string
    {
        return match($this->method) {
            'cash' => 'Cash',
            'check' => 'Check',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'online' => 'Online Payment',
            'other' => 'Other',
            default => ucfirst($this->method),
        };
    }

    /**
     * Scope to filter confirmed payments.
     */
    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('paid_at');
    }

    /**
     * Scope to filter pending payments.
     */
    public function scopePending($query)
    {
        return $query->whereNull('paid_at');
    }

    /**
     * Scope to filter payments by method.
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope to filter payments by date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_at', [$startDate, $endDate]);
    }
}
