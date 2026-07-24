<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function index()
    {
        return view('admin.seo-settings.index');
    }
}
