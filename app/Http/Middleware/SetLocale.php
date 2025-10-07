<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = LaravelLocalization::getCurrentLocale();
        app()->setLocale($locale);

        view()->share('dir', LaravelLocalization::getCurrentLocaleDirection());
        view()->share('current_locale', $locale);
        view()->share('available_locales', LaravelLocalization::getSupportedLocales());
        return $next($request);
    }
}
