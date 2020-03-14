<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ShoppingListController;
use App\Menuplan;
use PDF;

class ShoppingListPdfController extends Controller
{
    public function show(Menuplan $menuplan)
    {
        $items = (new ShoppingListController())->index($menuplan);
        $data = compact('items', 'menuplan');

        return PDF::loadView('pdfs.shopping-list', $data)
            ->setPaper('a4')
            ->download('shopping-list.pdf');
    }
}
