<?php

namespace App\Http\Controllers\API;

use App\Menuplan;
use App\Http\Controllers\Controller;

class ShoppingListController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        return $menuplan->ingredients
            ->load('meal')
            ->groupBy('item_id')
            ->filter(function ($ingredients) {
                return $ingredients->isEmpty() == false;
            })->map(function ($ingredients) {
                return [
                    'item_id' => $ingredients->first()->item_id,
                    'quantity' => round($ingredients->sum('quantity_for_shopping_list') + 0.0005, 3, PHP_ROUND_HALF_DOWN),
                    'title' => $ingredients->first()->item->title,
                    'unit' => $ingredients->first()->item->unit,
                    'meals' => $ingredients->map(function ($i) {
                        return [
                            'id' => $i->meal->id,
                            'quantity' => $i->quantity,
                            'title' => $i->meal->title,
                            'date' => $i->meal->date->format('Y-m-d'),
                            'start' => $i->meal->start,
                            'end' => $i->meal->end,
                        ];
                    }),
                ];
            })->sortBy('title')->values();
    }
}
