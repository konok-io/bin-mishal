@extends('layouts.admin')
@section('title', 'Ledger Entry Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-journal-text"></i> Ledger Entry</h1>
    <a href="{{ route('admin.ledger-entries.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Entry Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ $ledgerEntry->entry_date?->format('M d, Y') ?? 'N/A' }}</p>
                        <p><strong>Account:</strong> {{ $ledgerEntry->account?->code ?? 'N/A' }} - {{ $ledgerEntry->account?->name ?? 'N/A' }}</p>
                        <p><strong>Type:</strong>
                            @if($ledgerEntry->entry_type === 'debit')
                                <span class="badge bg-primary">Debit</span>
                            @else
                                <span class="badge bg-success">Credit</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Amount:</strong> {{ number_format($ledgerEntry->amount, 2) }}</p>
                        <p><strong>Reference:</strong> {{ $ledgerEntry->reference ?? 'N/A' }}</p>
                        <p><strong>Locked:</strong>
                            @if($ledgerEntry->is_locked)
                                <span class="badge bg-danger">Yes</span>
                            @else
                                <span class="badge bg-success">No</span>
                            @endif
                        </p>
                    </div>
                </div>
                <hr>
                <p><strong>Description:</strong></p>
                <p>{{ $ledgerEntry->description ?? 'No description provided' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.ledger-entries.edit', $ledgerEntry->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Entry
                </a>
                @if(!$ledgerEntry->is_locked)
                    <form action="{{ route('admin.ledger-entries.destroy', $ledgerEntry->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this entry?')">
                            <i class="bi bi-trash"></i> Delete Entry
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-lock"></i> This entry is locked and cannot be deleted.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
