<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampInstance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'camp_id',
        'year',
        'name',
        'description',
        'theme_description',
        'start_date',
        'end_date',
        'is_active',
        'max_capacity',
        'price',
        'age_from',
        'age_to',
        'grade_from',
        'grade_to',
        'registration_open_date',
        'registration_close_date',
        'theme_photos',
        'schedule_data',
        'additional_info',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_open_date' => 'date',
        'registration_close_date' => 'date',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'age_from' => 'integer',
        'age_to' => 'integer',
        'grade_from' => 'integer',
        'grade_to' => 'integer',
        'theme_photos' => 'array',
        'schedule_data' => 'array',
        'additional_info' => 'array',
    ];

    /**
     * Get the parent camp for this instance.
     */
    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    /**
     * Get the staff assigned to this camp instance.
     */
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_camp_instance_assignments')
            ->withPivot(['role_id', 'position', 'notes', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Get the users assigned to this camp instance (alias for staff).
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_camp_instance_assignments')
            ->withPivot(['role_id', 'position', 'notes', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Get the camp instance assignments for this instance.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(UserCampInstanceAssignment::class);
    }

    /**
     * Get the campers registered for this camp instance.
     */
    public function campers(): HasMany
    {
        return $this->hasMany(Camper::class);
    }


    /**
     * Scope to filter active camp instances.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter camp instances by year.
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to filter camp instances by date range.
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
     * Scope to filter upcoming camp instances.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now()->toDateString());
    }

    /**
     * Scope to filter current camp instances.
     */
    public function scopeCurrent($query)
    {
        $now = now()->toDateString();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    /**
     * Scope to filter past camp instances.
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now()->toDateString());
    }

    /**
     * Check if the camp instance is currently running.
     */
    public function isCurrentlyRunning(): bool
    {
        $now = now()->toDateString();
        return $this->start_date && $this->end_date &&
               $this->start_date <= $now && $this->end_date >= $now;
    }

    /**
     * Check if the camp instance is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->start_date && $this->start_date > now()->toDateString();
    }

    /**
     * Check if the camp instance is past.
     */
    public function isPast(): bool
    {
        return $this->end_date && $this->end_date < now()->toDateString();
    }

    /**
     * Check if registration is open for this camp instance.
     */
    public function isRegistrationOpen(): bool
    {
        $now = now()->toDateString();
        
        if (!$this->registration_open_date || !$this->registration_close_date) {
            return false;
        }
        
        return $this->registration_open_date <= $now && $this->registration_close_date >= $now;
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
     * Check if the camp instance is at capacity.
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
     * Get the display name for this instance.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->camp->display_name . ' ' . $this->year;
    }

    /**
     * Get the full display name including year.
     */
    public function getFullDisplayNameAttribute(): string
    {
        return $this->camp->display_name . ' ' . $this->year;
    }

    /**
     * Get age range as a formatted string.
     */
    public function getAgeRangeAttribute(): string
    {
        if ($this->age_from && $this->age_to) {
            return "Ages {$this->age_from}-{$this->age_to}";
        }
        return '';
    }

    /**
     * Get grade range as a formatted string.
     */
    public function getGradeRangeAttribute(): string
    {
        if ($this->grade_from && $this->grade_to) {
            $gradeNames = [
                1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th', 5 => '5th',
                6 => '6th', 7 => '7th', 8 => '8th', 9 => '9th', 10 => '10th',
                11 => '11th', 12 => '12th', 13 => 'Graduated Senior'
            ];
            
            $from = $gradeNames[$this->grade_from] ?? $this->grade_from;
            $to = $gradeNames[$this->grade_to] ?? $this->grade_to;
            
            return "Grades {$from}-{$to}";
        }
        return '';
    }

    /**
     * Get all camp instances including soft deleted ones (for admin purposes).
     */
    public static function withTrashed()
    {
        return parent::withTrashed();
    }

    /**
     * Get only soft deleted camp instances.
     */
    public static function onlyTrashed()
    {
        return parent::onlyTrashed();
    }

    /**
     * Restore a soft deleted camp instance.
     */
    public function restore(): bool
    {
        return parent::restore();
    }

    /**
     * Force delete a camp instance (permanently remove).
     */
    public function forceDelete(): bool
    {
        return parent::forceDelete();
    }
} 