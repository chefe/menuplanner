<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Rules\ItemInMenuplan;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Meal $meal)
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
                new ItemInMenuplan($meal->menuplan),
            ],
        ]);

        return $meal->ingredients()->create($data);
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $this->authorize('view', $ingredient->meal->menuplan);

        $data = $request->validate([
            'quantity' => 'required|numeric|min:0.001',
            'item_id' => [
                'required',
                new ItemInMenuplan($ingredient->meal->menuplan),
            ],
        ]);

        return tap($ingredient)->update($data);
    }

    public function destroy(Ingredient $ingredient)
    {
        $this->authorize('view', $ingredient->meal->menuplan);

        $ingredient->delete();
    }
}
