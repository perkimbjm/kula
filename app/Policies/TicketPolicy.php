<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
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
        return $user->can('view_any_ticket');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            if ($user->role_id === 2) {
                return $user->id === $ticket->user_id; // Warga hanya bisa melihat ticket sendiri
            }
            return true; // Role 1,3 bisa melihat semua
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('view_ticket');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            return true; // Role 1,2,3 selalu bisa create
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('create_ticket');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            if ($user->role_id === 2) {
                return $user->id === $ticket->user_id; // Warga hanya bisa update ticket sendiri
            }
            return true; // Role 1,3 bisa update semua
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('update_ticket');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Role 1,2,3 menggunakan hard-coded logic
        if (in_array($user->role_id, [1, 2, 3])) {
            if ($user->role_id === 2) {
                return $user->id === $ticket->user_id; // Warga hanya bisa delete ticket sendiri
            }
            return true; // Role 1,3 bisa delete semua
        }

        // Role 4+ menggunakan Shield permission
        return $user->can('delete_ticket');
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
        return $user->can('delete_any_ticket');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->can('force_delete_ticket');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_ticket');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->can('restore_ticket');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_ticket');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Ticket $ticket): bool
    {
        return $user->can('replicate_ticket');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_ticket');
    }
}
