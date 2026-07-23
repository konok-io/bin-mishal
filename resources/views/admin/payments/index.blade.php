@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-credit-card"></i> Payments</h1>
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Record Payment
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Payment No</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td><strong>{{ $payment->payment_no }}</strong></td>
                    <td>{{ $payment->customer->user->name ?? 'N/A' }}</td>
                    <td>SAR {{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                    <td>
                        @php
                            $statusClass = match($payment->status) {
                                'pending' => 'bg-warning',
                                'completed' => 'bg-success',
                                'refunded' => 'bg-info',
                                'failed' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td>{{ $payment->paid_at?->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No payments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $payments->withQueryString()->links() }}
    </div>
</div>
@endsection
