<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCampInstanceAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'camp_instance_id',
        'role_id',
        'position',
        'notes',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the user for this assignment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the camp instance for this assignment.
     */
    public function campInstance(): BelongsTo
    {
        return $this->belongsTo(CampInstance::class);
    }

    /**
     * Get the role for this assignment.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Scope to filter primary assignments.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to filter by camp instance.
     */
    public function scopeForCampInstance($query, $campInstanceId)
    {
        return $query->where('camp_instance_id', $campInstanceId);
    }

    /**
     * Scope to filter by role.
     */
    public function scopeWithRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get all assignments including soft deleted ones (for admin purposes).
     */
    public static function withTrashed()
    {
        return parent::withTrashed();
    }
} 