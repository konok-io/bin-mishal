@extends('layouts.admin')
@section('title', 'Invoices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-receipt"></i> Invoices</h1>
    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Invoice
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td><strong>{{ $invoice->invoice_no }}</strong></td>
                    <td>{{ $invoice->customer->user->name ?? 'N/A' }}</td>
                    <td>SAR {{ number_format($invoice->total, 2) }}</td>
                    <td>SAR {{ number_format($invoice->paid_amount, 2) }}</td>
                    <td>SAR {{ number_format($invoice->balance, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match($invoice->status) {
                                'draft' => 'bg-secondary',
                                'sent' => 'bg-info',
                                'partial' => 'bg-warning',
                                'paid' => 'bg-success',
                                'overdue' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                    </td>
                    <td>{{ $invoice->issue_date?->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No invoices found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $invoices->withQueryString()->links() }}
    </div>
</div>
@endsection
