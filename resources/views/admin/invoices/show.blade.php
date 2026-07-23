@extends('layouts.admin')
@section('title', 'Invoice Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-receipt"></i> Invoice: {{ $invoice->invoice_no }}</h1>
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Invoice Items</h5></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
                    <tbody>
                        @forelse($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>SAR {{ number_format($item->unit_price, 2) }}</td>
                            <td>SAR {{ number_format($item->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No items</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Customer:</th><td>{{ $invoice->customer->user->name ?? 'N/A' }}</td></tr>
                    <tr><th>Subtotal:</th><td>SAR {{ number_format($invoice->subtotal, 2) }}</td></tr>
                    <tr><th>Tax ({{ $invoice->tax_rate }}%):</th><td>SAR {{ number_format($invoice->tax_amount, 2) }}</td></tr>
                    <tr><th>Discount:</th><td>SAR {{ number_format($invoice->discount_amount, 2) }}</td></tr>
                    <tr><th><strong>Total:</strong></th><td><strong>SAR {{ number_format($invoice->total, 2) }}</strong></td></tr>
                    <tr><th>Paid:</th><td>SAR {{ number_format($invoice->paid_amount, 2) }}</td></tr>
                    <tr><th>Balance:</th><td>SAR {{ number_format($invoice->balance, 2) }}</td></tr>
                    <tr><th>Status:</th><td><span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($invoice->status) }}</span></td></tr>
                </table>
                @if($invoice->status === 'draft')
                <form action="{{ route('admin.invoices.send', $invoice->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send"></i> Send Invoice
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
