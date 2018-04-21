<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLocale($locale)
    {
        session()->put('locale', $locale);
        return back();
    }
}
