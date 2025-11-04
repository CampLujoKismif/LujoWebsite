<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camp extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the instances for this camp.
     */
    public function instances(): HasMany
    {
        return $this->hasMany(CampInstance::class);
    }

    /**
     * Get the current instance for this camp.
     */
    public function currentInstance(): ?CampInstance
    {
        return $this->instances()
            ->where('is_active', true)
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date')
            ->first();
    }

    /**
     * Get the assigned users for this camp.
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_camp_assignments')
            ->withPivot('position', 'is_primary')
            ->withTimestamps();
    }

    /**
     * Get the staff assignments for this camp.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(UserCampAssignment::class);
    }

    /**
     * Get the staff members for this camp.
     */
    public function staff(): BelongsToMany
    {
        return $this->assignedUsers();
    }

    /**
     * Get the notes for this camp.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'notable_id')
            ->where('notable_type', Camp::class);
    }

    /**
     * Get the session detail template for this camp.
     */
    public function sessionDetailTemplate(): HasOne
    {
        return $this->hasOne(CampSessionDetailTemplate::class);
    }

    /**
     * Scope a query to only include active camps.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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