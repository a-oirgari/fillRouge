<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $available = config('app.available_locales', ['fr', 'ar']);
        $locale = session('locale', config('app.locale', 'fr'));

        if (! in_array($locale, $available, true)) {
            $locale = 'fr';
        }

        app()->setLocale($locale);

        if (class_exists(Carbon::class)) {
            Carbon::setLocale($locale === 'ar' ? 'ar' : 'fr');
        }

        return $next($request);
    }
}
