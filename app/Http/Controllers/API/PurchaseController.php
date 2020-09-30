<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Models\Menuplan;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        return PurchaseResource::collection($menuplan->purchases);
    }

    public function show(Purchase $purchase)
    {
        $this->authorize('view', $purchase->menuplan);

        return $purchase->asResource();
    }

    public function store(Request $request, Menuplan $menuplan)
    {
        $this->authorize('view', $menuplan);

        $data = $this->getValidatedData($request, $menuplan);

        return $menuplan->purchases()->create($data)->asResource();
    }

    public function update(Request $request, Purchase $purchase)
    {
        $this->authorize('view', $purchase->menuplan);

        $data = $this->getValidatedData($request, $purchase->menuplan);

        return tap($purchase)->update($data)->asResource();
    }

    public function destroy(Purchase $purchase)
    {
        $this->authorize('view', $purchase->menuplan);

        $purchase->delete();
    }

    private function getValidatedData(Request $request, Menuplan $menuplan)
    {
        $minDate = 'after_or_equal:'.$menuplan->start->format('Y-m-d');
        $maxDate = 'before_or_equal:'.$menuplan->end->format('Y-m-d');

        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d|'.$minDate.'|'.$maxDate,
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        if (! isset($validated['notes']) || $validated['notes'] == null) {
            $validated['notes'] = '';
        }

        $validated['time'] = Carbon::parse($validated['date'].' '.$validated['time']);
        unset($validated['date']);

        return $validated;
    }
}
