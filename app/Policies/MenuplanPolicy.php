<?php

namespace App\Policies;

use App\User;
use App\Menuplan;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuplanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the menuplan.
     *
     * @param  \App\User  $user
     * @param  \App\Menuplan  $menuplan
     * @return mixed
     */
    public function view(User $user, Menuplan $menuplan)
    {
        return $menuplan->user_id == $user->id
            || $menuplan->users->contains($user);
    }

    /**
     * Determine whether the user can create menuplans.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create()
    {
        return true;
    }

    /**
     * Determine whether the user can update the menuplan.
     *
     * @param  \App\User  $user
     * @param  \App\Menuplan  $menuplan
     * @return mixed
     */
    public function update(User $user, Menuplan $menuplan)
    {
        return $menuplan->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the menuplan.
     *
     * @param  \App\User  $user
     * @param  \App\Menuplan  $menuplan
     * @return mixed
     */
    public function delete(User $user, Menuplan $menuplan)
    {
        return $this->update($user, $menuplan);
    }
}
