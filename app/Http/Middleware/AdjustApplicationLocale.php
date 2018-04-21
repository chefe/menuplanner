<?php

namespace App\Http\Middleware;

use Closure;

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
        app()->setLocale($this->getLocale());

        return $next($request);
    }

    private function getLocale()
    {
        return session()->get('locale', 'en');
    }
}
