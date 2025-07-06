<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the roles for this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Get the permissions for this user through their roles.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_roles', 'user_id', 'permission_id')
            ->wherePivotIn('role_id', $this->roles()->pluck('roles.id'));
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Check if the user has any of the specified roles.
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    /**
     * Check if the user has all of the specified roles.
     */
    public function hasAllRoles(array $roleNames): bool
    {
        $userRoleNames = $this->roles()->pluck('name')->toArray();
        return count(array_intersect($roleNames, $userRoleNames)) === count($roleNames);
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->exists();
    }

    /**
     * Check if the user has any of the specified permissions.
     */
    public function hasAnyPermission(array $permissionNames): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionNames) {
                $query->whereIn('name', $permissionNames);
            })
            ->exists();
    }

    /**
     * Check if the user is an admin (has any admin role).
     */
    public function isAdmin(): bool
    {
        return $this->roles()->where('is_admin', true)->exists();
    }

    /**
     * Assign a role to this user.
     */
    public function assignRole(Role $role): void
    {
        if (!$this->hasRole($role->name)) {
            $this->roles()->attach($role);
        }
    }

    /**
     * Remove a role from this user.
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role);
    }

    /**
     * Sync roles for this user (replace all existing roles).
     */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    /**
     * Get the camp assignments for this user.
     */
    public function campAssignments(): HasMany
    {
        return $this->hasMany(UserCampAssignment::class);
    }

    /**
     * Get the camps this user is assigned to.
     */
    public function assignedCamps(): BelongsToMany
    {
        return $this->belongsToMany(Camp::class, 'user_camp_assignments')
            ->withPivot(['role_id', 'position', 'notes', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Get the primary camp assignment for this user.
     */
    public function primaryCampAssignment()
    {
        return $this->campAssignments()->where('is_primary', true)->first();
    }

    /**
     * Get the primary camp for this user.
     */
    public function primaryCamp()
    {
        $assignment = $this->primaryCampAssignment();
        return $assignment ? $assignment->camp : null;
    }

    /**
     * Check if the user is assigned to a specific camp.
     */
    public function isAssignedToCamp($campId): bool
    {
        return $this->campAssignments()->where('camp_id', $campId)->exists();
    }

    /**
     * Check if the user has a specific role in a specific camp.
     */
    public function hasRoleInCamp(string $roleName, $campId): bool
    {
        return $this->campAssignments()
            ->where('camp_id', $campId)
            ->whereHas('role', function ($query) use ($roleName) {
                $query->where('name', $roleName);
            })
            ->exists();
    }

    /**
     * Check if the user has a specific permission in a specific camp.
     */
    public function hasPermissionInCamp(string $permissionName, $campId): bool
    {
        return $this->campAssignments()
            ->where('camp_id', $campId)
            ->whereHas('role.permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->exists();
    }

    /**
     * Get all camps where the user has a specific permission.
     */
    public function getCampsWithPermission(string $permissionName): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assignedCamps()
            ->whereHas('assignments.role.permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->get();
    }

    /**
     * Assign a user to a camp with a specific role.
     */
    public function assignToCamp(Camp $camp, Role $role, array $attributes = []): UserCampAssignment
    {
        $defaults = [
            'position' => null,
            'notes' => null,
            'is_primary' => false,
        ];

        $attributes = array_merge($defaults, $attributes);

        return $this->campAssignments()->create([
            'camp_id' => $camp->id,
            'role_id' => $role->id,
            'position' => $attributes['position'],
            'notes' => $attributes['notes'],
            'is_primary' => $attributes['is_primary'],
        ]);
    }

    /**
     * Remove a user from a camp assignment.
     */
    public function removeFromCamp($campId, $roleId = null): void
    {
        $query = $this->campAssignments()->where('camp_id', $campId);
        
        if ($roleId) {
            $query->where('role_id', $roleId);
        }
        
        $query->delete();
    }

    /**
     * Check if the user can access camp-specific data.
     * This is a helper method for camp-scoped permissions.
     */
    public function canAccessCampData($campId, ?string $permissionName = null): bool
    {
        // Super admins can access all camp data
        if ($this->isAdmin() && $this->hasRole('super_admin')) {
            return true;
        }

        // Check if user is assigned to this camp
        if (!$this->isAssignedToCamp($campId)) {
            return false;
        }

        // If no specific permission is requested, just check camp assignment
        if (!$permissionName) {
            return true;
        }

        // Check if user has the specific permission in this camp
        return $this->hasPermissionInCamp($permissionName, $campId);
    }

    /**
     * Get all users including soft deleted ones (for admin purposes).
     */
    public static function withTrashed()
    {
        return parent::withTrashed();
    }

    /**
     * Get only soft deleted users.
     */
    public static function onlyTrashed()
    {
        return parent::onlyTrashed();
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore(): bool
    {
        return parent::restore();
    }

    /**
     * Force delete a user (permanently remove).
     */
    public function forceDelete(): bool
    {
        return parent::forceDelete();
    }
}
