<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $supported = config('locales.supported', ['fr']);

        abort_unless(in_array($locale, $supported, true), 400);

        session(['locale' => $locale]);

        return redirect()->back();
    }
}
