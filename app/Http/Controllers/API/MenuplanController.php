<?php

namespace App\Http\Controllers\API;

use App\Menuplan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuplanResource;

class MenuplanController extends Controller
{
    public function index(Request $request)
    {
        return MenuplanResource::collection($request->user()->menuplans);
    }

    public function store(Request $request)
    {
        $data = $this->getValidatedData($request);

        return Menuplan::create(array_merge($data, [
            'user_id' => $request->user()->id,
        ]))->asResource();
    }

    public function update(Request $request, Menuplan $menuplan)
    {
        $this->authorize('update', $menuplan);

        $data = $this->getValidatedData($request);

        return tap($menuplan)->update($data)->asResource();
    }

    public function destroy(Request $request, Menuplan $menuplan)
    {
        $this->authorize('delete', $menuplan);

        $menuplan->delete();
    }

    private function getValidatedData(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|min:3',
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d',
            'people' => 'required|integer|min:1',
        ]);
    }
}
