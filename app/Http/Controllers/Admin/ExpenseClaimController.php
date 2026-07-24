<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseClaimController extends Controller
{
    public function index()
    {
        return view('admin.expense-claims.index');
    }
}
