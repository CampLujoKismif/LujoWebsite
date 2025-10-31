<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_admin',
        'type',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Role type constants
     */
    const TYPE_WEB_ACCESS = 'web_access';
    const TYPE_CAMP_SESSION = 'camp_session';

    /**
     * Get all role types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_WEB_ACCESS => 'Web Access Role',
            self::TYPE_CAMP_SESSION => 'Camp Session Role',
        ];
    }

    /**
     * Scope a query to only include roles of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include web access roles.
     */
    public function scopeWebAccess($query)
    {
        return $query->where('type', self::TYPE_WEB_ACCESS);
    }

    /**
     * Scope a query to only include camp session roles.
     */
    public function scopeCampSession($query)
    {
        return $query->where('type', self::TYPE_CAMP_SESSION);
    }

    /**
     * Get the permissions for this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Get the users that have this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'model_id')
            ->where('user_roles.model_type', User::class);
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Assign a permission to this role.
     */
    public function assignPermission(Permission $permission): void
    {
        if (!$this->hasPermission($permission->name)) {
            $this->permissions()->attach($permission);
        }
    }

    /**
     * Remove a permission from this role.
     */
    public function removePermission(Permission $permission): void
    {
        $this->permissions()->detach($permission);
    }

    /**
     * Get all roles including soft deleted ones (for admin purposes).
     */
    public static function withTrashed()
    {
        return parent::withTrashed();
    }

    /**
     * Get only soft deleted roles.
     */
    public static function onlyTrashed()
    {
        return parent::onlyTrashed();
    }

    /**
     * Restore a soft deleted role.
     */
    public function restore(): bool
    {
        return parent::restore();
    }

    /**
     * Force delete a role (permanently remove).
     */
    public function forceDelete(): bool
    {
        return parent::forceDelete();
    }
} 