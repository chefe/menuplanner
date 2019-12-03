<?php

namespace App\Policies;

use App\Invitation;
use App\User;
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
