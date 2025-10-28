<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_per_person_per_day',
        'deposit_amount',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price_per_person_per_day' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the current active pricing.
     */
    public static function current(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Calculate total cost for a reservation.
     */
    public function calculateTotal(int $numberOfPeople, int $numberOfDays): float
    {
        return $numberOfPeople * $numberOfDays * $this->price_per_person_per_day;
    }

    /**
     * Scope for active pricing.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
