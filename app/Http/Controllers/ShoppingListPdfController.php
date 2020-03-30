<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ShoppingListController;
use App\Menuplan;
use App\Purchase;
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
        $title = $purchase->time->formatLocalized('%d %B %H:%M');
        return $this->generatePdf($items, $title);
    }

    private function generatePdf($items, $title)
    {
        $data = compact('items', 'title');

        return PDF::loadView('pdfs.shopping-list', $data)
            ->setPaper('a4')
            ->download('shopping-list.pdf');
    }
}
