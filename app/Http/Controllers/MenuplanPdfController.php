<?php

namespace App\Http\Controllers;

use PDF;
use App\Menuplan;
use Illuminate\Support\Collection;

class MenuplanPdfController extends Controller
{
    public function show(Menuplan $menuplan)
    {
        $menuplan->load('meals');

        $dayCount = $menuplan->end->diffInDays($menuplan->start);
        $days = Collection::times($dayCount + 1, function ($number) use ($menuplan) {
            return $number - 1;
        })->mapWithKeys(function ($number) use ($menuplan) {
            $date = $menuplan->start->addDays($number)->format('Y-m-d');
            $meals = $menuplan->meals->filter(function ($item) use ($date) {
                return $item->date->format('Y-m-d') == $date;
            })->sortBy('start');

            return [$date => $meals];
        });

        $meals = $menuplan->meals->sortBy(function ($m) {
            return $m->date->format('Y-m-d').'-'.$m->start;
        });

        $data = compact('menuplan', 'days', 'meals');

        return PDF::loadView('pdfs.menuplan', $data)
            ->download('menuplan.pdf');
    }
}
