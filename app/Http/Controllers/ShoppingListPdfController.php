<?php

namespace App\Http\Controllers;

use PDF;
use App\Menuplan;
use App\Http\Controllers\API\ShoppingListController;

class ShoppingListPdfController extends Controller
{
    public function show(Menuplan $menuplan)
    {
        $items = (new ShoppingListController())->index($menuplan);
        $data = compact('items', 'menuplan');

        return PDF::loadView('pdfs.shopping-list', $data)
            ->download('shopping-list.pdf');
    }
}
