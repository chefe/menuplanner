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

        $data = $this->getValidatedData($request, $menuplan);

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

        $data = $this->getValidatedData($request, $meal->menuplan);

        return tap($meal)->update($data)->asResource();
    }

    public function destroy(Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        $meal->delete();
    }

    private function getValidatedData(Request $request, Menuplan $menuplan)
    {
        $minDate = 'after_or_equal:'.$menuplan->start->format('Y-m-d');
        $maxDate = 'before_or_equal:'.$menuplan->end->format('Y-m-d');

        $validated = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'nullable|string',
            'date' => 'required|date_format:Y-m-d|'.$minDate.'|'.$maxDate,
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|min:start',
            'people' => 'nullable|integer|min:1',
            'ingredients_for' => 'required|integer|min:1',
        ]);

        if (! isset($validated['description']) || $validated['description'] == null) {
            $validated['description'] = '';
        }

        return $validated;
    }
}
