@extends('layouts.admin')
@section('title', 'Payment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-credit-card"></i> Payment: {{ $payment->payment_no }}</h1>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <tr><th width="30%">Payment No:</th><td><strong>{{ $payment->payment_no }}</strong></td></tr>
            <tr><th>Customer:</th><td>{{ $payment->customer->user->name ?? 'N/A' }}</td></tr>
            <tr><th>Amount:</th><td>SAR {{ number_format($payment->amount, 2) }}</td></tr>
            <tr><th>Method:</th><td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td></tr>
            <tr><th>Transaction ID:</th><td>{{ $payment->transaction_id ?? '-' }}</td></tr>
            <tr><th>Status:</th><td><span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($payment->status) }}</span></td></tr>
            <tr><th>Created:</th><td>{{ $payment->created_at->format('d M Y H:i') }}</td></tr>
            <tr><th>Paid At:</th><td>{{ $payment->paid_at?->format('d M Y H:i') ?? '-' }}</td></tr>
        </table>

        @if($payment->status === 'pending')
        <form action="{{ route('admin.payments.complete', $payment->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Mark as Complete
            </button>
        </form>
        @elseif($payment->status === 'completed')
        <form action="{{ route('admin.payments.refund', $payment->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Refund
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
