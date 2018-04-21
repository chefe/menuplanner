<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    public function setLocale($locale)
    {
        session()->put('locale', $locale);

        return back();
    }
}
