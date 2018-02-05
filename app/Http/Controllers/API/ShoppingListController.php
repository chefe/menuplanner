<?php

namespace App\Http\Controllers\API;

use App\Menuplan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoppingListController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        return $menuplan->ingredients
            ->groupBy('item_id')
            ->filter(function ($ingredients) {
                return $ingredients->isEmpty() == false;
            })->map(function ($ingredients) {
                return [
                    'item_id' => $ingredients->first()->item_id,
                    'quantity' => $ingredients->sum('quantity'),
                    'meals' => $ingredients->map(function ($i) {
                        return [
                            'id' => $i->meal_id,
                            'quantity' => $i->quantity,
                        ];
                    }),
                ];
            })->values();
    }
}
