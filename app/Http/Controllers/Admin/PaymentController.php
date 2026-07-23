<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['customer.user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        return view('admin.payments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:bank_transfer,credit_card,debit_card,cash,check,sadad,mada,apple_pay,wallet',
        ]);

        Payment::create([
            'payment_no' => Payment::generateNo(),
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment created');
    }

    public function show(int $id)
    {
        $payment = Payment::with(['customer.user', 'createdBy'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function complete(int $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->complete();
        return redirect()->back()->with('success', 'Payment completed');
    }

    public function refund(int $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->refund();
        return redirect()->back()->with('success', 'Payment refunded');
    }
}
