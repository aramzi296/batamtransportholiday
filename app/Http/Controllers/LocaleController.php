<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Supported locales.
     */
    protected array $supportedLocales = ['id', 'en'];

    /**
     * Switch application locale and redirect back.
     */
    public function switch(string $locale, Request $request)
    {
        if (! in_array($locale, $this->supportedLocales, true)) {
            $locale = config('app.locale', 'id');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        $previous = $request->headers->get('referer') ?? url('/');
        return redirect()->to($previous);
    }
}
