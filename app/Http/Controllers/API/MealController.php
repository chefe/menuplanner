<?php

namespace App\Http\Controllers\API;

use App\Meal;
use App\Menuplan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;

class MealController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        return MealResource::collection($menuplan->meals);
    }

    public function store(Request $request, Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        $data = $this->getValidatedData($request);

        return $menuplan->meals()->create($data)->asResource();
    }

    public function show(Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        return $meal->asResource();
    }

    public function update(Request $request, Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        $data = $this->getValidatedData($request);

        return tap($meal)->update($data)->asResource();
    }

    public function destroy(Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        $meal->delete();
    }

    private function getValidatedData(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'nullable|string',
            'date' => 'required|date_format:Y-m-d',
            'start' => 'required|date_format:H:i:s',
            'end' => 'required|date_format:H:i:s|after:start',
            'people' => 'nullable|integer|min:1',
        ]);
    }
}
