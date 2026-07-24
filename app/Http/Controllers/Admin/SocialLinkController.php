<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        return view('admin.social-links.index');
    }
}
