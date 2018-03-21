<?php

namespace App\Http\Controllers\API;

use App\Invitation;
use App\Http\Controllers\Controller;

class InvitationController extends Controller
{
    public function index()
    {
        return auth()->user()->getOpenInvitations();
    }

    public function destroy(Invitation $invitation)
    {
        $this->authorize('delete', $invitation);

        $invitation->delete();
    }
}
