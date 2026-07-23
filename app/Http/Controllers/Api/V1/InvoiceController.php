<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Invoice::with(['customer.user', 'createdBy']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        $invoices = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($invoices);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'nullable|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                'invoice_no' => Invoice::generateNo(),
                'customer_id' => $request->customer_id,
                'title' => $request->title,
                'description' => $request->description,
                'tax_rate' => $request->tax_rate ?? 15,
                'discount_amount' => $request->discount_amount ?? 0,
                'issue_date' => $request->issue_date ?? now(),
                'due_date' => $request->due_date,
                'status' => 'draft',
                'created_by' => $request->user()->id,
            ]);

            $subtotal = 0;
            foreach ($request->items as $item) {
                $quantity = $item['quantity'] ?? 1;
                $unitPrice = $item['unit_price'];
                $total = $quantity * $unitPrice;
                $subtotal += $total;

                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
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

            return $this->success(
                $invoice->load('customer.user', 'items'),
                'Invoice created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create invoice: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $invoice = Invoice::with(['customer.user', 'createdBy', 'items'])->findOrFail($id);

        return $this->success($invoice);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status !== 'draft') {
            return $this->error('Only draft invoices can be updated');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $invoice->update($request->only([
            'title', 'description', 'tax_rate', 'discount_amount', 'due_date'
        ]));

        $invoice->calculateTotals();

        return $this->success($invoice->load('items'), 'Invoice updated successfully');
    }

    public function send(int $id): JsonResponse
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'sent']);
        }

        return $this->success($invoice, 'Invoice sent successfully');
    }

    public function addPayment(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->addPayment((float) $request->amount);

        return $this->success($invoice->load('items'), 'Payment recorded successfully');
    }

    public function items(int $id): JsonResponse
    {
        $invoice = Invoice::findOrFail($id);
        $items = $invoice->items;

        return $this->success($items);
    }

    public function addItem(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'description' => 'required|string|max:500',
            'quantity' => 'nullable|numeric|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);

        if ($invoice->status !== 'draft') {
            return $this->error('Only draft invoices can be modified');
        }

        $quantity = $request->quantity ?? 1;
        $total = $quantity * $request->unit_price;

        $invoice->items()->create([
            'description' => $request->description,
            'quantity' => $quantity,
            'unit_price' => $request->unit_price,
            'total' => $total,
        ]);

        $invoice->calculateTotals();

        return $this->success($invoice->load('items'), 'Item added successfully');
    }

    public function markOverdue(): JsonResponse
    {
        $overdue = Invoice::overdue()->update(['status' => 'overdue']);

        return $this->success(['count' => $overdue], 'Overdue invoices marked');
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Invoice::count(),
            'draft' => Invoice::where('status', 'draft')->count(),
            'sent' => Invoice::where('status', 'sent')->count(),
            'partial' => Invoice::where('status', 'partial')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
            'total_invoiced' => Invoice::sum('total'),
            'total_collected' => Invoice::sum('paid_amount'),
            'total_due' => Invoice::sum('balance'),
        ];

        return $this->success($stats);
    }
}
