<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Invitation $invitation)
    {
        return $user->id == $invitation->user_id ||
            $user->id == $invitation->menuplan->user_id;
    }
}
