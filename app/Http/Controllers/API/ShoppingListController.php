<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Menuplan;

class ShoppingListController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        return $menuplan->ingredients
            ->load('meal')
            ->groupBy('item_id')
            ->filter(function ($ingredients) {
                return ! $ingredients->isEmpty();
            })->map(function ($ingredients) {
                return [
                    'item_id' => $ingredients->first()->item_id,
                    'quantity' => $this->round($ingredients->sum('quantity_for_shopping_list')),
                    'title' => $ingredients->first()->item->title,
                    'unit' => $ingredients->first()->item->unit,
                    'meals' => $ingredients->map(function ($i) {
                        return [
                            'id' => $i->meal->id,
                            'quantity' => $this->round($i->quantity_for_shopping_list),
                            'title' => $i->meal->title,
                            'date' => $i->meal->date->format('Y-m-d'),
                            'start' => $i->meal->start,
                            'end' => $i->meal->end,
                        ];
                    }),
                ];
            })->sortBy('title')->values();
    }

    private function round($number)
    {
        return round($number + 0.0005, 3, PHP_ROUND_HALF_DOWN);
    }
}
