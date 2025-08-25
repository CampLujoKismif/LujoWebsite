<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'camp_instance_id',
        'camper_id',
        'status',
        'balance_cents',
        'amount_paid_cents',
        'forms_complete',
        'enrolled_at',
        'notes',
    ];

    protected $casts = [
        'balance_cents' => 'integer',
        'amount_paid_cents' => 'integer',
        'forms_complete' => 'boolean',
        'enrolled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the camp instance for this enrollment.
     */
    public function campInstance(): BelongsTo
    {
        return $this->belongsTo(CampInstance::class);
    }

    /**
     * Get the camp for this enrollment.
     */
    public function camp(): BelongsTo
    {
        return $this->campInstance->camp();
    }

    /**
     * Get the camper for this enrollment.
     */
    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }

    /**
     * Get the family for this enrollment.
     */
    public function family(): BelongsTo
    {
        return $this->camper->family();
    }

    /**
     * Get the payments for this enrollment.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the form responses for this enrollment.
     */
    public function formResponses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }

    /**
     * Get the documents for this enrollment.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the notes for this enrollment.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'notable_id')
            ->where('notable_type', Enrollment::class);
    }

    /**
     * Get the total amount in dollars.
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->balance_cents / 100;
    }

    /**
     * Get the amount paid in dollars.
     */
    public function getAmountPaidAttribute(): float
    {
        return $this->amount_paid_cents / 100;
    }

    /**
     * Get the balance in dollars.
     */
    public function getBalanceAttribute(): float
    {
        return $this->balance_cents / 100;
    }

    /**
     * Check if the enrollment is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->amount_paid_cents >= $this->balance_cents;
    }

    /**
     * Check if the enrollment has a balance.
     */
    public function hasBalance(): bool
    {
        return $this->balance_cents > $this->amount_paid_cents;
    }

    /**
     * Get the outstanding balance in cents.
     */
    public function getOutstandingBalanceCentsAttribute(): int
    {
        return max(0, $this->balance_cents - $this->amount_paid_cents);
    }

    /**
     * Get the outstanding balance in dollars.
     */
    public function getOutstandingBalanceAttribute(): float
    {
        return $this->outstanding_balance_cents / 100;
    }

    /**
     * Check if the enrollment is active (not cancelled).
     */
    public function isActive(): bool
    {
        return !in_array($this->status, ['cancelled']);
    }

    /**
     * Check if the enrollment is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if the enrollment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the enrollment is waitlisted.
     */
    public function isWaitlisted(): bool
    {
        return $this->status === 'waitlisted';
    }

    /**
     * Check if the enrollment is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the enrollment is registered but awaiting payment.
     */
    public function isRegisteredAwaitingPayment(): bool
    {
        return $this->status === 'registered_awaiting_payment';
    }

    /**
     * Check if the enrollment is registered and fully paid.
     */
    public function isRegisteredFullyPaid(): bool
    {
        return $this->status === 'registered_fully_paid';
    }

    /**
     * Check if the enrollment is registered (either awaiting payment or fully paid).
     */
    public function isRegistered(): bool
    {
        return in_array($this->status, ['registered_awaiting_payment', 'registered_fully_paid']);
    }

    /**
     * Mark enrollment as registered awaiting payment.
     */
    public function markAsRegisteredAwaitingPayment(): void
    {
        $this->update(['status' => 'registered_awaiting_payment']);
    }

    /**
     * Mark enrollment as registered fully paid.
     */
    public function markAsRegisteredFullyPaid(): void
    {
        $this->update(['status' => 'registered_fully_paid']);
    }

    /**
     * Update the balance after a payment.
     */
    public function updateBalanceAfterPayment(int $paymentAmountCents): void
    {
        $this->amount_paid_cents += $paymentAmountCents;
        $this->save();
    }

    /**
     * Check if all required forms are complete.
     */
    public function checkFormsCompletion(): bool
    {
        // This would be implemented based on the forms system
        // For now, return the stored value
        return $this->forms_complete;
    }
}
