<?php

namespace App\Policies;

use App\User;
use App\Invitation;
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
