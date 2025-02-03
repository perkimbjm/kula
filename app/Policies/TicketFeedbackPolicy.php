<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TicketFeedback;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketFeedbackPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_ticket::feedback');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketFeedback $ticketFeedback): bool
    {
        if ($user->role_id === 3) {
            return $ticketFeedback->admin_id === $user->id;
        }
        return $user->role_id === 1 || $user->id === $ticketFeedback->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_ticket::feedback');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketFeedback $ticketFeedback): bool
    {
        if ($user->role_id === 3) {
            return $ticketFeedback->admin_id === $user->id;
        }
        if ($user->role_id === 2) {
            return $user->id === $ticketFeedback->user_id;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketFeedback $ticketFeedback): bool
    {
        return $user->can('delete_ticket::feedback');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_ticket::feedback');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, TicketFeedback $ticketFeedback): bool
    {
        return $user->can('force_delete_ticket::feedback');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_ticket::feedback');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, TicketFeedback $ticketFeedback): bool
    {
        return $user->can('restore_ticket::feedback');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_ticket::feedback');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, TicketFeedback $ticketFeedback): bool
    {
        return $user->can('replicate_ticket::feedback');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_ticket::feedback');
    }
}
