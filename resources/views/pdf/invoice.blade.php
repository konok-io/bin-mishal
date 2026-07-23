<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .invoice-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .company-info h1 { font-size: 24px; color: #1a56db; }
        .company-info p { color: #666; font-size: 11px; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 28px; color: #1a56db; }
        .invoice-meta { color: #666; margin-top: 10px; }
        .info-grid { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-box { width: 45%; }
        .info-box h4 { color: #1a56db; margin-bottom: 10px; border-bottom: 2px solid #1a56db; padding-bottom: 5px; }
        .info-box p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #1a56db; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .amount-col { text-align: right; }
        tfoot td { font-weight: bold; background: #f9fafb; }
        .totals { margin-left: auto; width: 300px; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .totals-row.total { font-size: 16px; color: #1a56db; border: 2px solid #1a56db; margin-top: 10px; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 10px; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; }
        .status.paid { background: #dcfce7; color: #166534; }
        .status.unpaid { background: #fef9c3; color: #854d0e; }
        .status.overdue { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1>{{ $company['name'] ?? config('app.name') }}</h1>
                <p>{{ $company['address'] ?? 'Saudi Arabia' }}</p>
                <p>Phone: {{ $company['phone'] ?? 'N/A' }}</p>
                <p>Email: {{ $company['email'] ?? 'N/A' }}</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-meta">
                    <p><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
                    <p><strong>Date:</strong> {{ $invoice->issue_date?->format('d M Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $invoice->due_date?->format('d M Y') }}</p>
                    <p class="status {{ $invoice->status }}">{{ strtoupper($invoice->status) }}</p>
                </div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h4>Bill To</h4>
                <p><strong>{{ $invoice->customer->user->name ?? 'N/A' }}</strong></p>
                <p>{{ $invoice->customer->user->email ?? '' }}</p>
                <p>{{ $invoice->customer->user->phone ?? '' }}</p>
                @if($invoice->customer->company_name)
                <p>{{ $invoice->customer->company_name }}</p>
                @endif
            </div>
            <div class="info-box">
                <h4>From</h4>
                <p><strong>{{ $company['name'] ?? config('app.name') }}</strong></p>
                <p>{{ $company['address'] ?? '' }}</p>
                <p>{{ $company['phone'] ?? '' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount-col">Qty</th>
                    <th class="amount-col">Unit Price</th>
                    <th class="amount-col">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="amount-col">{{ $item->quantity }}</td>
                    <td class="amount-col">SAR {{ number_format($item->unit_price, 2) }}</td>
                    <td class="amount-col">SAR {{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>SAR {{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span>Tax ({{ $invoice->tax_rate }}%)</span>
                <span>SAR {{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @if($invoice->discount_amount > 0)
            <div class="totals-row">
                <span>Discount</span>
                <span>- SAR {{ number_format($invoice->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="totals-row total">
                <span>Total</span>
                <span>SAR {{ number_format($invoice->total, 2) }}</span>
            </div>
            @if($invoice->paid_amount > 0)
            <div class="totals-row">
                <span>Paid</span>
                <span>SAR {{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div class="totals-row total">
                <span>Balance Due</span>
                <span>SAR {{ number_format($invoice->balance, 2) }}</span>
            </div>
            @endif
        </div>

        @if($invoice->description)
        <div style="margin-top: 30px;">
            <h4>Notes</h4>
            <p>{{ $invoice->description }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>{{ $company['name'] ?? config('app.name') }} | {{ $company['address'] ?? '' }}</p>
        </div>
    </div>
</body>
</html>
