<?php

namespace App\Http\Controllers\API;

use App\Item;
use App\Menuplan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function index(Request $request, Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        return $menuplan->items;
    }

    public function store(Request $request, Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        $data = $request->validate([
            'title' => 'required|string|min:2',
            'unit' => 'required|string|min:1',
        ]);

        return $menuplan->items()->create($data);
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('view', $item->menuplan);

        $data = $request->validate([
            'title' => 'required|string|min:2',
            'unit' => 'required|string|min:1',
        ]);

        return tap($item)->update($data);
    }

    public function destroy(Request $request, Item $item)
    {
        $this->authorize('view', $item->menuplan);

        $item->delete();
    }
}
