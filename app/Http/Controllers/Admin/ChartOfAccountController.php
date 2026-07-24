<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index()
    {
        return view('admin.chart-of-accounts.index');
    }
}
