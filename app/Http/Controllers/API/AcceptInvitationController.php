<?php

namespace App\Http\Controllers\API;

use App\Invitation;
use App\Http\Controllers\Controller;

class AcceptInvitationController extends Controller
{
    public function store(Invitation $invitation)
    {
        if ($invitation->canBeAcceptedBy(auth()->user())) {
            $invitation->accept();

            return response(null, 200);
        }

        return response(null, 403);
    }

    public function destroy(Invitation $invitation)
    {
        if ($invitation->canBeAcceptedBy(auth()->user())) {
            $invitation->delete();

            return response(null, 200);
        }

        return response(null, 403);
    }
}
