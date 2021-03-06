<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;

class AdjustApplicationLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $this->getLocale();
        App::setLocale($locale);

        Carbon::setLocale($locale);
        setlocale(LC_TIME, $this->convertToLocaleNameForSystem($locale));

        return $next($request);
    }

    private function getLocale()
    {
        return session()->get('locale', config('app.locale'));
    }

    private function convertToLocaleNameForSystem($locale)
    {
        return $locale == 'de' ? 'German' : 'English';
    }
}
