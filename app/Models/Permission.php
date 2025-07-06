<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get the users that have this permission through their roles.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id')
            ->wherePivotIn('role_id', $this->roles()->pluck('roles.id'));
    }

    /**
     * Scope to filter permissions by group.
     */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get all permissions including soft deleted ones (for admin purposes).
     */
    public static function withTrashed()
    {
        return parent::withTrashed();
    }

    /**
     * Get only soft deleted permissions.
     */
    public static function onlyTrashed()
    {
        return parent::onlyTrashed();
    }

    /**
     * Restore a soft deleted permission.
     */
    public function restore(): bool
    {
        return parent::restore();
    }

    /**
     * Force delete a permission (permanently remove).
     */
    public function forceDelete(): bool
    {
        return parent::forceDelete();
    }
} 