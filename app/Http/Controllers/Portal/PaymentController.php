<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('customer_id', auth()->user()->customer->id)
            ->with('invoice')
            ->latest()
            ->paginate(10);

        return view('portal.payments.index', compact('payments'));
    }

    public function show(int $id)
    {
        $payment = Payment::where('customer_id', auth()->user()->customer->id)
            ->with(['invoice', 'createdBy'])
            ->findOrFail($id);

        return view('portal.payments.show', compact('payment'));
    }
}
