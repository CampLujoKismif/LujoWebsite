<?php

namespace App\Policies;

use App\Models\FormTemplate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormTemplatePolicy
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
    public function view(User $user, FormTemplate $formTemplate): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can view global templates and templates for their camp instances
            if ($formTemplate->isGlobal()) {
                return true;
            }

            if ($formTemplate->isCampSession()) {
                return $user->camps()->where('camp_id', $formTemplate->campInstance->camp_id)->exists();
            }
        }

        if ($user->hasRole('parent')) {
            // Parents can view global templates and templates for camp instances their campers are enrolled in
            if ($formTemplate->isGlobal()) {
                return true;
            }

            if ($formTemplate->isCampSession()) {
                return $user->accessibleCampers()
                    ->whereHas('enrollments', function ($query) use ($formTemplate) {
                        $query->where('camp_instance_id', $formTemplate->camp_instance_id);
                    })
                    ->exists();
            }
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
    public function update(User $user, FormTemplate $formTemplate): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can update global templates and templates for their camp instances
            if ($formTemplate->isGlobal()) {
                return true;
            }

            if ($formTemplate->isCampSession()) {
                return $user->camps()->where('camp_id', $formTemplate->campInstance->camp_id)->exists();
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormTemplate $formTemplate): bool
    {
        if ($user->hasRole('system-admin')) {
            return true;
        }

        if ($user->hasRole('camp-manager')) {
            // Camp managers can delete templates for their camp instances
            if ($formTemplate->isCampSession()) {
                return $user->camps()->where('camp_id', $formTemplate->campInstance->camp_id)->exists();
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormTemplate $formTemplate): bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormTemplate $formTemplate): bool
    {
        return $user->hasRole('system-admin');
    }
}
