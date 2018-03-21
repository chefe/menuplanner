<?php

namespace App\Http\Controllers\API;

use App\Menuplan;
use App\Invitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvitationController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        $this->authorize('update', $menuplan);

        return $menuplan->invitations;
    }

    public function store(Request $request, Menuplan $menuplan)
    {
        $this->authorize('update', $menuplan);

        $data = $this->getValidatedData($request);

        if ($menuplan->hasInvitationFor($data['email'])) {
            return response(null, 200);
        }

        $menuplan->invitations()->create($data);

        return response(null, 201);
    }

    public function destroy(Invitation $invitation)
    {
        $this->authorize('delete', $invitation);

        $invitation->delete();
    }

    private function getValidatedData(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
        ]);
    }
}
