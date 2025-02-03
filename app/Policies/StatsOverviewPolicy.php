<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatsOverviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the stats overview widget.
     */
    public function view(User $user): bool
    {
        return $user->can('widget_StatsOverview');
    }
}
