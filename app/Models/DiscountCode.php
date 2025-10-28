<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_type',
        'discount_value',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'description',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the reservations that used this discount code.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(RentalReservation::class);
    }

    /**
     * Check if the discount code is valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the discount amount for a given total.
     */
    public function calculateDiscount(float $total): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return $total * ($this->discount_value / 100);
        }

        return min($this->discount_value, $total);
    }

    /**
     * Increment the usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Scope for active discount codes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for rental discount codes.
     */
    public function scopeRental($query)
    {
        return $query->where('type', 'rental');
    }

    /**
     * Scope for camper discount codes.
     */
    public function scopeCamper($query)
    {
        return $query->where('type', 'camper');
    }
}
