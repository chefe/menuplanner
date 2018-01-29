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
        abort_unless($request->user()->id == $meal->menuplan->user_id, 403);

        return $meal->ingredients;
    }

    public function store(Request $request, Meal $meal)
    {
        abort_unless($request->user()->id == $meal->menuplan->user_id, 403);

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
        abort_unless($request->user()->id == $ingredient->meal->menuplan->user_id, 403);

        $data = $request->validate([
            'quantity' => 'required|numeric|min:0.001',
        ]);

        return tap($ingredient)->update($data);
    }

    public function destroy(Request $request, Ingredient $ingredient)
    {
        abort_unless($request->user()->id == $ingredient->meal->menuplan->user_id, 403);

        $ingredient->delete();
    }
}
