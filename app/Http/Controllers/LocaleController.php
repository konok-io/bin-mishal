<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function change(Request $request)
    {
        $locale = $request->input('locale');
        
        if (in_array($locale, ['en', 'bn', 'ar'])) {
            Session::put('locale', $locale);
            app()->setLocale($locale);
        }
        
        return redirect()->back();
    }
}
