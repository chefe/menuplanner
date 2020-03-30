<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ShoppingListController;
use App\Menuplan;
use App\Purchase;
use Carbon\Carbon;
use PDF;

class ShoppingListPdfController extends Controller
{
    public function index(Menuplan $menuplan)
    {
        $items = (new ShoppingListController())->index($menuplan);

        return $this->generatePdf($items, $menuplan->title);
    }

    public function show(Purchase $purchase)
    {
        $items = (new ShoppingListController())->show($purchase);
        $date = $this->getLocalizedDate($purchase->time);
        $title = $date->shortDayName.$date->format(' d, H:i');

        return $this->generatePdf($items, $title);
    }

    private function generatePdf($items, $title)
    {
        $items = $items->map(function ($item) {
            $item['meals'] = collect($item['meals'])->map(function ($meal) {
                $dt = $this->getLocalizedDate($meal['date']);
                $meal['formatedDate'] = $dt->shortDayName.' '.$dt->format('d');

                return $meal;
            })->all();

            return $item;
        });

        $data = compact('items', 'title');

        return PDF::loadView('pdfs.shopping-list', $data)
            ->setPaper('a4')
            ->download('shopping-list.pdf');
    }

    private function getLocalizedDate($date)
    {
        $locale = session()->get('locale', config('app.locale'));

        return Carbon::parse($date)->locale($locale);
    }
}
