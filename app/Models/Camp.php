<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'max_capacity',
        'price',
        'age_from',
        'age_to',
        'grade_from',
        'grade_to',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'age_from' => 'integer',
        'age_to' => 'integer',
        'grade_from' => 'integer',
        'grade_to' => 'integer',
    ];

    /**
     * Get the staff assigned to this camp.
     */
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_camp_assignments')
            ->withPivot(['role_id', 'position', 'notes', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Get the users assigned to this camp (alias for staff).
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_camp_assignments')
            ->withPivot(['role_id', 'position', 'notes', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Get the camp assignments for this camp.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(UserCampAssignment::class);
    }

    /**
     * Get the campers registered for this camp.
     */
    public function campers(): HasMany
    {
        return $this->hasMany(Camper::class);
    }

    /**
     * Get the registrations for this camp.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Scope to filter active camps.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter camps by date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($subQ) use ($startDate, $endDate) {
                  $subQ->where('start_date', '<=', $startDate)
                       ->where('end_date', '>=', $endDate);
              });
        });
    }

    /**
     * Check if the camp is currently running.
     */
    public function isCurrentlyRunning(): bool
    {
        $now = now()->toDateString();
        return $this->start_date && $this->end_date &&
               $this->start_date <= $now && $this->end_date >= $now;
    }

    /**
     * Check if the camp is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->start_date && $this->start_date > now()->toDateString();
    }

    /**
     * Check if the camp is past.
     */
    public function isPast(): bool
    {
        return $this->end_date && $this->end_date < now()->toDateString();
    }

    /**
     * Get the current capacity (number of registered campers).
     */
    public function getCurrentCapacityAttribute(): int
    {
        // For now, return 0 since Camper model doesn't exist yet
        // This can be updated when the Camper model is implemented
        return 0;
    }

    /**
     * Check if the camp is at capacity.
     */
    public function isAtCapacity(): bool
    {
        if (!$this->max_capacity) {
            return false;
        }
        return $this->current_capacity >= $this->max_capacity;
    }

    /**
     * Get available spots.
     */
    public function getAvailableSpotsAttribute(): ?int
    {
        if (!$this->max_capacity) {
            return null;
        }
        return max(0, $this->max_capacity - $this->current_capacity);
    }

    /**
     * Get all camps including soft deleted ones (for admin purposes).
     */
    public static function withTrashed()
    {
        return parent::withTrashed();
    }

    /**
     * Get only soft deleted camps.
     */
    public static function onlyTrashed()
    {
        return parent::onlyTrashed();
    }

    /**
     * Restore a soft deleted camp.
     */
    public function restore(): bool
    {
        return parent::restore();
    }

    /**
     * Force delete a camp (permanently remove).
     */
    public function forceDelete(): bool
    {
        return parent::forceDelete();
    }
} 