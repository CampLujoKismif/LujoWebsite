<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RentalPricing;
use Illuminate\Auth\Access\Response;

class RentalPricingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_rental_pricing');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RentalPricing $rentalPricing): bool
    {
        return $user->hasPermission('manage_rental_pricing');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage_rental_pricing');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RentalPricing $rentalPricing): bool
    {
        return $user->hasPermission('manage_rental_pricing');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RentalPricing $rentalPricing): bool
    {
        return $user->hasPermission('manage_rental_pricing');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RentalPricing $rentalPricing): bool
    {
        return $user->hasPermission('manage_rental_pricing');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RentalPricing $rentalPricing): bool
    {
        return $user->hasPermission('manage_rental_pricing');
    }
}
