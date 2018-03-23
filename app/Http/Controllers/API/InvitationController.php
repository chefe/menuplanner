<?php

namespace App\Http\Controllers\API;

use App\Invitation;
use App\Http\Controllers\Controller;

class InvitationController extends Controller
{
    public function index()
    {
        return auth()->user()->getInvitations();
    }

    public function destroy(Invitation $invitation)
    {
        $this->authorize('update', $invitation->menuplan);

        $invitation->delete();
    }
}
