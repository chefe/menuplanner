<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    public function store($locale)
    {
        session()->put('locale', $locale);

        return back();
    }
}
