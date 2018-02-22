<?php

namespace App\Http\Controllers;

use PDF;
use App\Menuplan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MenuplanPdfController extends Controller
{
    /** */
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

        $durationFormatter = function ($meal) {
            $startTimeParts = explode(':', $meal->start);
            $endTimeParts = explode(':', $meal->end);
            return vsprintf('%02d:%02d - %02d:%02d', [
                $startTimeParts[0],
                $startTimeParts[1],
                $endTimeParts[0],
                $endTimeParts[1],
            ]);
        };

        $data = compact('menuplan', 'days', 'durationFormatter');

        return PDF::loadView('pdfs.menuplan', $data)
            ->download('menuplan.pdf');
    }
}
