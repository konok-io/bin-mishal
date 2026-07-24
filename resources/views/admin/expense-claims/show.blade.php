@extends('layouts.admin')
@section('title', 'Expense Claim Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-receipt"></i> Expense Claim</h1>
    <a href="{{ route('expense-claims.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Claim Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Claim Number:</strong> {{ $expenseClaim->claim_number }}</p>
                        <p><strong>Employee:</strong> {{ $expenseClaim->user->name ?? 'N/A' }}</p>
                        <p><strong>Title:</strong> {{ $expenseClaim->title }}</p>
                        <p><strong>Category:</strong> {{ $expenseClaim->category ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Amount:</strong> {{ number_format($expenseClaim->amount, 2) }}</p>
                        <p><strong>Expense Date:</strong> {{ $expenseClaim->expense_date->format('M d, Y') }}</p>
                        <p><strong>Status:</strong>
                            @php
                                $statusClass = match($expenseClaim->status) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'reimbursed' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $expenseClaim->status }}</span>
                        </p>
                    </div>
                </div>
                <hr>
                <p><strong>Description:</strong></p>
                <p>{{ $expenseClaim->description ?? 'No description provided' }}</p>
                @if($expenseClaim->notes)
                <hr>
                <p><strong>Admin Notes:</strong></p>
                <p>{{ $expenseClaim->notes }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('expense-claims.edit', $expenseClaim->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Claim
                </a>
                @if($expenseClaim->status !== 'reimbursed')
                    <form action="{{ route('expense-claims.destroy', $expenseClaim->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this claim?')">
                            <i class="bi bi-trash"></i> Delete Claim
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-info-circle"></i> This claim is reimbursed and cannot be deleted.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
