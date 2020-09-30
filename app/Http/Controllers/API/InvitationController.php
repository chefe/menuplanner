<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Invitation;

class InvitationController extends Controller
{
    public function index()
    {
        return auth()->user()->getInvitations();
    }

    public function destroy(Invitation $invitation)
    {
        $this->authorize('delete', $invitation);

        $invitation->delete();
    }
}
