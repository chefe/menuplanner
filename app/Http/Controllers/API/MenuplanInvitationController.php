<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Menuplan;
use Illuminate\Http\Request;

class MenuplanInvitationController extends Controller
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

    private function getValidatedData(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
        ]);
    }
}
