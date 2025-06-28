<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TicketResponse;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketResponsePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            return true; // Role 1,2,3 selalu bisa melihat
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('view_any_ticket::response');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketResponse $ticketResponse): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            if ($user->role_id === 2) {
                return $user->id === $ticketResponse->user_id; // Warga hanya bisa melihat response untuk ticket sendiri
            }
            if ($user->role_id === 3) {
                return $user->id === $ticketResponse->admin_id; // Admin hanya bisa melihat response yang dibuat sendiri
            }
            return true; // Role 1 bisa melihat semua
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('view_ticket::response');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            return in_array($user->role_id, [1, 3]); // Hanya role 1,3 yang bisa create response
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('create_ticket::response');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketResponse $ticketResponse): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            if ($user->role_id === 2) {
                return false; // Warga tidak bisa update response
            }
            if ($user->role_id === 3) {
                return $user->id === $ticketResponse->admin_id; // Admin hanya bisa update response yang dibuat sendiri
            }
            return true; // Role 1 bisa update semua
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('update_ticket::response');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketResponse $ticketResponse): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            if ($user->role_id === 2) {
                return false; // Warga tidak bisa delete response
            }
            if ($user->role_id === 3) {
                return $user->id === $ticketResponse->admin_id; // Admin hanya bisa delete response yang dibuat sendiri
            }
            return true; // Role 1 bisa delete semua
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('delete_ticket::response');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            return in_array($user->role_id, [1, 3]); // Hanya role 1,3 yang bisa bulk delete
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('delete_any_ticket::response');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, TicketResponse $ticketResponse): bool
    {
        return $user->can('force_delete_ticket::response');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_ticket::response');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, TicketResponse $ticketResponse): bool
    {
        return $user->can('restore_ticket::response');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_ticket::response');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, TicketResponse $ticketResponse): bool
    {
        return $user->can('replicate_ticket::response');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_ticket::response');
    }
}
