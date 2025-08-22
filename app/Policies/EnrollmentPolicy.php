<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['system-admin', 'camp-manager', 'parent']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            return $user->camps()->where('camp_id', $enrollment->campInstance->camp_id)->exists();
        }

        if ($user->hasRole('parent')) {
            return $user->families()->where('family_id', $enrollment->camper->family_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['system-admin', 'camp-manager', 'parent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            return $user->camps()->where('camp_id', $enrollment->campInstance->camp_id)->exists();
        }

        if ($user->hasRole('parent')) {
            return $user->families()->where('family_id', $enrollment->camper->family_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            return $user->camps()->where('camp_id', $enrollment->campInstance->camp_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return $user->hasRole('system-admin');
    }
}
