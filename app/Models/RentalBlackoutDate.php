<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RentalBlackoutDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'reason',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if a date range conflicts with any active blackout dates
     */
    public static function hasConflict($startDate, $endDate, $excludeId = null): bool
    {
        $query = static::where('is_active', true)
            ->where(function ($q) use ($startDate, $endDate) {
                // Check if the requested dates overlap with any blackout period
                $q->where(function ($query) use ($startDate, $endDate) {
                    // Blackout starts before or during the requested period and ends during or after
                    $query->where('start_date', '<=', $endDate)
                          ->where('end_date', '>=', $startDate);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get conflicting blackout dates for a date range
     */
    public static function getConflicts($startDate, $endDate, $excludeId = null)
    {
        $query = static::where('is_active', true)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                          ->where('end_date', '>=', $startDate);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }

    /**
     * Get all active blackout dates
     */
    public static function getActive()
    {
        return static::where('is_active', true)
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Get upcoming active blackout dates
     */
    public static function getUpcoming()
    {
        return static::where('is_active', true)
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Check if this blackout date is in the past
     */
    public function isPast(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Check if this blackout date is current (active right now)
     */
    public function isCurrent(): bool
    {
        $now = now();
        return $this->start_date->lte($now) && $this->end_date->gte($now);
    }

    /**
     * Check if this blackout date is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture();
    }

    /**
     * Get the number of days in this blackout period
     */
    public function getDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}

