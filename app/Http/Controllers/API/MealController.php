<?php

namespace App\Http\Controllers\API;

use App\Meal;
use App\Menuplan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MealController extends Controller
{
    public function index(Request $request, Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        return $menuplan->meals;
    }

    public function store(Request $request, Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        $data = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'nullable|string',
            'date' => 'required|date_format:Y-m-d',
            'start' => 'required|date_format:H:i:s',
            'end' => 'required|date_format:H:i:s|after:start',
            'people' => 'nullable|integer|min:1',
        ]);

        return $menuplan->meals()->create($data);
    }

    public function update(Request $request, Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        $data = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'nullable|string',
            'date' => 'required|date_format:Y-m-d',
            'start' => 'required|date_format:H:i:s',
            'end' => 'required|date_format:H:i:s|after:start',
            'people' => 'nullable|integer|min:1',
        ]);

        return tap($meal)->update($data);
    }

    public function destroy(Request $request, Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        $meal->delete();
    }
}
