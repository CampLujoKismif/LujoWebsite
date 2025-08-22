<?php

namespace App\Policies;

use App\Models\Camper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CamperPolicy
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
    public function view(User $user, Camper $camper): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can view campers enrolled in their camps
            return $camper->enrollments()
                ->whereHas('campInstance', function ($query) use ($user) {
                    $query->whereHas('camp', function ($subQuery) use ($user) {
                        $subQuery->whereIn('id', $user->camps()->pluck('camp_id'));
                    });
                })
                ->exists();
        }

        if ($user->hasRole('parent')) {
            return $user->families()->where('family_id', $camper->family_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['system-admin', 'parent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Camper $camper): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can update campers enrolled in their camps
            return $camper->enrollments()
                ->whereHas('campInstance', function ($query) use ($user) {
                    $query->whereHas('camp', function ($subQuery) use ($user) {
                        $subQuery->whereIn('id', $user->camps()->pluck('camp_id'));
                    });
                })
                ->exists();
        }

        if ($user->hasRole('parent')) {
            return $user->families()->where('family_id', $camper->family_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Camper $camper): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('parent')) {
            return $user->families()->where('family_id', $camper->family_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Camper $camper): bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Camper $camper): bool
    {
        return $user->hasRole('system-admin');
    }
}
