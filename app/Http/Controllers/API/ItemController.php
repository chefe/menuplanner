<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\Menuplan;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        return ItemResource::collection($menuplan->items->sortBy(function ($item) {
            return strtoupper($item->title);
        }));
    }

    public function store(Request $request, Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        $data = $this->getValidatedData($request);

        return $menuplan->items()->create($data)->asResource();
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('view', $item->menuplan);

        $data = $this->getValidatedData($request);

        return tap($item)->update($data)->asResource();
    }

    public function destroy(Item $item)
    {
        $this->authorize('view', $item->menuplan);

        $item->delete();
    }

    private function getValidatedData(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|min:2',
            'unit' => 'required|string|min:1',
        ]);
    }
}
