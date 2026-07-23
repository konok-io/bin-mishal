<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['customer.user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        return view('admin.invoices.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'nullable|string|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'invoice_no' => Invoice::generateNo(),
                'customer_id' => $request->customer_id,
                'title' => $request->title,
                'tax_rate' => $request->tax_rate ?? 15,
                'discount_amount' => $request->discount_amount ?? 0,
                'issue_date' => now(),
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            $subtotal = 0;
            foreach ($request->items as $item) {
                $total = $item['unit_price'];
                $subtotal += $total;
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => 1,
                    'unit_price' => $item['unit_price'],
                    'total' => $total,
                ]);
            }

            $taxAmount = $subtotal * ($invoice->tax_rate / 100);
            $invoice->subtotal = $subtotal;
            $invoice->tax_amount = $taxAmount;
            $invoice->total = $subtotal + $taxAmount - $invoice->discount_amount;
            $invoice->balance = $invoice->total;
            $invoice->save();

            DB::commit();
            return redirect()->route('admin.invoices.index')->with('success', 'Invoice created');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create invoice');
        }
    }

    public function show(int $id)
    {
        $invoice = Invoice::with(['customer.user', 'items', 'createdBy'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function send(int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->send();
        return redirect()->back()->with('success', 'Invoice sent');
    }
}
