<?php

namespace App\Http\Controllers\API;

use App\Meal;
use App\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IngredientController extends Controller
{
    public function index(Request $request, Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        return $meal->ingredients;
    }

    public function store(Request $request, Meal $meal)
    {
        $this->authorize('view', $meal->menuplan);

        $data = $request->validate([
            'quantity' => 'required|numeric|min:0.001',
            'item_id' => [
                'required',
                'in:'.$meal->menuplan->items->pluck('id')->implode(','),
            ],
        ]);

        return $meal->ingredients()->create($data);
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $this->authorize('view', $ingredient->meal->menuplan);

        $data = $request->validate([
            'quantity' => 'required|numeric|min:0.001',
        ]);

        return tap($ingredient)->update($data);
    }

    public function destroy(Request $request, Ingredient $ingredient)
    {
        $this->authorize('view', $ingredient->meal->menuplan);

        $ingredient->delete();
    }
}
