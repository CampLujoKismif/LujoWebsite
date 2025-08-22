<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalRecordPolicy
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
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can view medical records of campers enrolled in their camps
            return $medicalRecord->camper->enrollments()
                ->whereHas('campInstance', function ($query) use ($user) {
                    $query->whereHas('camp', function ($subQuery) use ($user) {
                        $subQuery->whereIn('id', $user->camps()->pluck('camp_id'));
                    });
                })
                ->exists();
        }

        if ($user->hasRole('parent')) {
            return $user->families()->where('family_id', $medicalRecord->camper->family_id)->exists();
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
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can update medical records of campers enrolled in their camps
            return $medicalRecord->camper->enrollments()
                ->whereHas('campInstance', function ($query) use ($user) {
                    $query->whereHas('camp', function ($subQuery) use ($user) {
                        $subQuery->whereIn('id', $user->camps()->pluck('camp_id'));
                    });
                })
                ->exists();
        }

        if ($user->hasRole('parent')) {
            return $user->families()->where('family_id', $medicalRecord->camper->family_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can delete medical records of campers enrolled in their camps
            return $medicalRecord->camper->enrollments()
                ->whereHas('campInstance', function ($query) use ($user) {
                    $query->whereHas('camp', function ($subQuery) use ($user) {
                        $subQuery->whereIn('id', $user->camps()->pluck('camp_id'));
                    });
                })
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalRecord $medicalRecord): bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalRecord $medicalRecord): bool
    {
        return $user->hasRole('system-admin');
    }
}
