<?php
// Policy untuk mengatur hak akses Worksheet berbasis permission Spatie
namespace App\Policies;

use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorksheetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_worksheet');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Worksheet $worksheet): bool
    {
        return $user->can('view_worksheet');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_worksheet');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Worksheet $worksheet): bool
    {
        return $user->can('update_worksheet');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Worksheet $worksheet): bool
    {
        return $user->can('delete_worksheet');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_worksheet');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Worksheet $worksheet): bool
    {
        return $user->can('force_delete_worksheet');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_worksheet');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Worksheet $worksheet): bool
    {
        return $user->can('restore_worksheet');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_worksheet');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Worksheet $worksheet): bool
    {
        return $user->can('replicate_worksheet');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_worksheet');
    }
}
// Perubahan: WorksheetPolicy sekarang mengikuti struktur dan method WorkPolicy, semua permission worksheet di-handle sesuai standar Spatie.
