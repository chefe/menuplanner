<?php

namespace App\Http\Controllers\API;

use App\Menuplan;
use App\Invitation;
use Illuminate\Http\Request;
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
