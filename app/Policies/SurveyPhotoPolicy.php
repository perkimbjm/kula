<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SurveyPhoto;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyPhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_survey::photo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurveyPhoto $surveyPhoto): bool
    {
        return $user->can('view_survey::photo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_survey::photo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurveyPhoto $surveyPhoto): bool
    {
        return $user->can('update_survey::photo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurveyPhoto $surveyPhoto): bool
    {
        return $user->can('delete_survey::photo');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_survey::photo');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SurveyPhoto $surveyPhoto): bool
    {
        return $user->can('force_delete_survey::photo');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_survey::photo');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SurveyPhoto $surveyPhoto): bool
    {
        return $user->can('restore_survey::photo');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_survey::photo');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SurveyPhoto $surveyPhoto): bool
    {
        return $user->can('replicate_survey::photo');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_survey::photo');
    }
}
