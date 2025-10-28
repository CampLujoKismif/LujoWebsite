<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RentalBlackoutDate;

class RentalBlackoutDatePolicy
{
    /**
     * Determine if the user can view any blackout dates.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage rentals') || $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can view a blackout date.
     */
    public function view(User $user, RentalBlackoutDate $blackoutDate): bool
    {
        return $user->hasPermissionTo('manage rentals') || $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can create blackout dates.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage rentals') || $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can update blackout dates.
     */
    public function update(User $user, RentalBlackoutDate $blackoutDate): bool
    {
        return $user->hasPermissionTo('manage rentals') || $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can delete blackout dates.
     */
    public function delete(User $user, RentalBlackoutDate $blackoutDate): bool
    {
        return $user->hasPermissionTo('manage rentals') || $user->hasRole('Super Admin');
    }
}

