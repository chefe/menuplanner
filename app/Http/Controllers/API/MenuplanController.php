<?php

namespace App\Http\Controllers\API;

use App\Menuplan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuplanController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->menuplans;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:3',
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d',
            'people' => 'required|integer|min:1',
        ]);

        return Menuplan::create(array_merge($data, [
            'user_id' => $request->user()->id,
        ]))->asResource();
    }

    public function update(Request $request, Menuplan $menuplan)
    {
        abort_unless($menuplan->user_id == $request->user()->id, 403);

        $data = $request->validate([
            'title' => 'required|string|min:3',
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d',
            'people' => 'required|integer|min:1',
        ]);

        return tap($menuplan)->update($data)->asResource();
    }

    public function destroy(Request $request, Menuplan $menuplan)
    {
        abort_unless($menuplan->user_id == $request->user()->id, 403);

        $menuplan->delete();
    }
}
