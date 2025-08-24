<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'owner_user_id',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'insurance_provider',
        'insurance_policy_number',
        'insurance_group_number',
        'insurance_phone',
        'home_congregation_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the owner user for this family.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * Get the users associated with this family.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'family_users')
            ->withPivot('role_in_family')
            ->withTimestamps();
    }

    /**
     * Get the family user relationships.
     */
    public function familyUsers(): HasMany
    {
        return $this->hasMany(FamilyUser::class);
    }

    /**
     * Get the campers in this family.
     */
    public function campers(): HasMany
    {
        return $this->hasMany(Camper::class);
    }

    /**
     * Get the enrollments for this family.
     */
    public function enrollments(): HasMany
    {
        return $this->hasManyThrough(Enrollment::class, Camper::class);
    }

    /**
     * Get the home congregation for this family.
     */
    public function homeCongregation(): BelongsTo
    {
        return $this->belongsTo(ChurchCongregation::class, 'home_congregation_id');
    }

    /**
     * Get the documents/attachments for this family.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Check if a user is a member of this family.
     */
    public function hasUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is the owner of this family.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_user_id === $user->id;
    }

    /**
     * Get the role of a user in this family.
     */
    public function getUserRole(User $user): ?string
    {
        $familyUser = $this->familyUsers()->where('user_id', $user->id)->first();
        return $familyUser ? $familyUser->role_in_family : null;
    }

    /**
     * Add a user to this family with a specific role.
     */
    public function addUser(User $user, string $role = 'parent'): void
    {
        if (!$this->hasUser($user)) {
            $this->users()->attach($user->id, ['role_in_family' => $role]);
        }
    }

    /**
     * Remove a user from this family.
     */
    public function removeUser(User $user): void
    {
        $this->users()->detach($user->id);
    }
}
