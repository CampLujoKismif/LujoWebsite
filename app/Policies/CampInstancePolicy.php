<?php

namespace App\Policies;

use App\Models\CampInstance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampInstancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['system-admin', 'camp-manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CampInstance $campInstance): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            return $user->camps()->where('camp_id', $campInstance->camp_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['system-admin', 'camp-manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CampInstance $campInstance): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            return $user->camps()->where('camp_id', $campInstance->camp_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CampInstance $campInstance): bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CampInstance $campInstance): bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CampInstance $campInstance): bool
    {
        return $user->hasRole('system-admin');
    }
}
