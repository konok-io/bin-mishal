<?php

declare(strict_types=1);

namespace App\Services\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Create invoice with items
     */
    public function createInvoice(array $data): Invoice
    {
        $invoice = Invoice::create([
            'invoice_no' => Invoice::generateNo(),
            'customer_id' => $data['customer_id'],
            'title' => $data['title'] ?? 'Invoice',
            'description' => $data['description'] ?? null,
            'subtotal' => 0,
            'tax_rate' => $data['tax_rate'] ?? 15,
            'tax_amount' => 0,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'total' => 0,
            'paid_amount' => 0,
            'balance' => 0,
            'status' => 'draft',
            'issue_date' => $data['issue_date'] ?? now(),
            'due_date' => $data['due_date'] ?? now()->addDays(14),
            'created_by' => auth()->id(),
        ]);

        // Add items
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $item['unit_price'],
                    'total' => ($item['quantity'] ?? 1) * $item['unit_price'],
                ]);
            }
        }

        // Calculate totals
        $invoice->calculateTotals();

        return $invoice;
    }

    /**
     * Add item to invoice
     */
    public function addItem(Invoice $invoice, array $itemData): InvoiceItem
    {
        $item = $invoice->items()->create([
            'description' => $itemData['description'],
            'quantity' => $itemData['quantity'] ?? 1,
            'unit_price' => $itemData['unit_price'],
            'total' => ($itemData['quantity'] ?? 1) * $itemData['unit_price'],
        ]);

        $invoice->calculateTotals();

        return $item;
    }

    /**
     * Remove item from invoice
     */
    public function removeItem(Invoice $invoice, int $itemId): bool
    {
        $item = $invoice->items()->find($itemId);
        if (!$item) {
            return false;
        }

        $item->delete();
        $invoice->calculateTotals();

        return true;
    }

    /**
     * Apply discount
     */
    public function applyDiscount(Invoice $invoice, float $discount): Invoice
    {
        $invoice->update(['discount_amount' => $discount]);
        $invoice->calculateTotals();

        return $invoice;
    }

    /**
     * Send invoice
     */
    public function sendInvoice(Invoice $invoice, ?string $email = null): bool
    {
        $invoice->send();

        // Queue email notification
        // Mail::to($email ?? $invoice->customer->user->email)
        //     ->queue(new InvoiceMail($invoice));

        return true;
    }

    /**
     * Generate PDF
     */
    public function generatePdf(Invoice $invoice): string
    {
        $data = [
            'invoice' => $invoice->load(['customer.user', 'items', 'createdBy']),
            'company' => [
                'name' => config('app.name'),
                'address' => config('binmishal.address'),
                'phone' => config('binmishal.phone'),
                'email' => config('binmishal.email'),
                'logo' => config('binmishal.logo'),
            ],
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);

        // Save to storage
        $filename = "invoices/{$invoice->invoice_no}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Mark overdue invoices
     */
    public function markOverdueInvoices(): int
    {
        return Invoice::whereIn('status', ['sent', 'partial'])
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }

    /**
     * Get invoice statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_invoices' => Invoice::count(),
            'draft' => Invoice::where('status', 'draft')->count(),
            'sent' => Invoice::where('status', 'sent')->count(),
            'partial' => Invoice::where('status', 'partial')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'overdue' => Invoice::overdue()->count(),
            'total_amount' => Invoice::sum('total'),
            'total_paid' => Invoice::sum('paid_amount'),
            'total_outstanding' => Invoice::sum('balance'),
            'this_month' => [
                'invoices' => Invoice::whereMonth('issue_date', now()->month)->count(),
                'amount' => Invoice::whereMonth('issue_date', now()->month)->sum('total'),
            ],
        ];
    }
}
