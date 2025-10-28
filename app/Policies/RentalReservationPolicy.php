<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RentalReservation;
use Illuminate\Auth\Access\Response;

class RentalReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RentalReservation $rentalReservation): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RentalReservation $rentalReservation): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RentalReservation $rentalReservation): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RentalReservation $rentalReservation): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RentalReservation $rentalReservation): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can cancel the reservation.
     */
    public function cancel(User $user, RentalReservation $rentalReservation): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']) && $rentalReservation->canBeCancelled();
    }

    /**
     * Determine whether the user can process refunds.
     */
    public function refund(User $user, RentalReservation $rentalReservation): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']) && 
               $rentalReservation->status === 'confirmed' && 
               $rentalReservation->stripe_payment_intent_id;
    }

    /**
     * Determine whether the user can manage pricing.
     */
    public function managePricing(User $user): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can manage discount codes.
     */
    public function manageDiscounts(User $user): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }

    /**
     * Determine whether the user can view rental analytics.
     */
    public function viewAnalytics(User $user): bool
    {
        return $user->hasRole(['system-admin', 'rental-admin']);
    }
}
