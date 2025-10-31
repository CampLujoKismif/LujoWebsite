<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'must_change_password',
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
            'must_change_password' => 'boolean',
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

    // Note: Role and permission methods are provided by the Spatie HasRoles trait
    // Custom role methods have been removed to avoid conflicts with Spatie package
    
    /**
     * Check if the user is an admin (has any admin role).
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('system-admin');
    }

    /**
     * Get the families this user belongs to.
     */
    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_users')
            ->withPivot('role_in_family')
            ->withTimestamps();
    }

    /**
     * Get the families this user owns.
     */
    public function ownedFamilies(): HasMany
    {
        return $this->hasMany(Family::class, 'owner_user_id');
    }

    /**
     * Get the campers this user has access to through their families.
     */
    public function accessibleCampers()
    {
        return Camper::whereIn('family_id', $this->families()->pluck('families.id'));
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
     * Get the camp instance assignments for this user.
     */
    public function campInstanceAssignments(): HasMany
    {
        return $this->hasMany(UserCampInstanceAssignment::class);
    }

    /**
     * Get the camp instances (sessions) this user is assigned to.
     */
    public function assignedCampInstances(): BelongsToMany
    {
        return $this->belongsToMany(CampInstance::class, 'user_camp_instance_assignments')
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
     * Check if the user is assigned to a specific camp instance/session.
     */
    public function isAssignedToCampInstance($campInstanceId): bool
    {
        return $this->campInstanceAssignments()->where('camp_instance_id', $campInstanceId)->exists();
    }

    /**
     * Check if the user has a specific role in a specific camp instance/session.
     */
    public function hasRoleInCampInstance(string $roleName, $campInstanceId): bool
    {
        return $this->campInstanceAssignments()
            ->where('camp_instance_id', $campInstanceId)
            ->whereHas('role', function ($query) use ($roleName) {
                $query->where('name', $roleName);
            })
            ->exists();
    }

    /**
     * Assign a user to a camp instance/session with a specific role.
     */
    public function assignToCampInstance(CampInstance $campInstance, Role $role, array $attributes = []): UserCampInstanceAssignment
    {
        $defaults = [
            'position' => null,
            'notes' => null,
            'is_primary' => false,
        ];

        $attributes = array_merge($defaults, $attributes);

        return $this->campInstanceAssignments()->create([
            'camp_instance_id' => $campInstance->id,
            'role_id' => $role->id,
            'position' => $attributes['position'],
            'notes' => $attributes['notes'],
            'is_primary' => $attributes['is_primary'],
        ]);
    }

    /**
     * Remove a user from a camp instance/session assignment.
     */
    public function removeFromCampInstance($campInstanceId, $roleId = null): void
    {
        $query = $this->campInstanceAssignments()->where('camp_instance_id', $campInstanceId);
        
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
        // System admins can access all camp data
        if ($this->hasRole('system-admin')) {
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
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

}
