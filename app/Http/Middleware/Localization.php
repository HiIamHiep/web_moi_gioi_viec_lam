<?php

namespace App\Http\Middleware;

use Closure;
use http\Cookie;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = session()->get('locale');
        if (empty($locale)){
            $locale = $request->cookie('locale');
        }
        if (!in_array($locale, config('app.locales'))) {
            $locale = config('app.available_locales');
        }

        app()->setLocale($locale);
        setcookie('locale', $locale, time() + (86400 * 30));

        return $next($request);
    }
}
