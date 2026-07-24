@extends('layouts.admin')
@section('title', 'Account Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-book"></i> {{ $chartOfAccount->code }} - {{ $chartOfAccount->name }}</h1>
    <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Code:</strong> {{ $chartOfAccount->code }}</p>
                        <p><strong>Name:</strong> {{ $chartOfAccount->name }}</p>
                        <p><strong>Description:</strong> {{ $chartOfAccount->description ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Type:</strong>
                            @php
                                $typeColors = ['asset' => 'primary', 'liability' => 'danger', 'equity' => 'success', 'revenue' => 'info', 'expense' => 'warning'];
                            @endphp
                            <span class="badge bg-{{ $typeColors[$chartOfAccount->type] ?? 'secondary' }}">{{ ucfirst($chartOfAccount->type) }}</span>
                        </p>
                        <p><strong>Category:</strong> {{ $chartOfAccount->category ? ucwords(str_replace('_', ' ', $chartOfAccount->category)) : 'N/A' }}</p>
                        <p><strong>Normal Balance:</strong> {{ ucfirst($chartOfAccount->normal_balance) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Balance Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h3 class="text-primary">{{ number_format($chartOfAccount->balance, 2) }}</h3>
                        <p class="text-muted">Current Balance</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-success">{{ number_format($chartOfAccount->debit_total, 2) }}</h3>
                        <p class="text-muted">Total Debits</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-danger">{{ number_format($chartOfAccount->credit_total, 2) }}</h3>
                        <p class="text-muted">Total Credits</p>
                    </div>
                </div>
            </div>
        </div>

        @if($chartOfAccount->children->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Child Accounts</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chartOfAccount->children as $child)
                        <tr>
                            <td>{{ $child->code }}</td>
                            <td>{{ $child->name }}</td>
                            <td>{{ number_format($child->balance, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('chart-of-accounts.edit', $chartOfAccount->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Account
                </a>
                @if(!$chartOfAccount->is_system)
                    <form action="{{ route('chart-of-accounts.destroy', $chartOfAccount->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this account?')">
                            <i class="bi bi-trash"></i> Delete Account
                        </button>
                    </form>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> System accounts cannot be deleted.
                    </div>
                @endif
            </div>
        </div>

        @if($chartOfAccount->parent)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Parent Account</h5>
            </div>
            <div class="card-body">
                <p><strong>{{ $chartOfAccount->parent->code }}</strong> - {{ $chartOfAccount->parent->name }}</p>
                <a href="{{ route('chart-of-accounts.show', $chartOfAccount->parent->id) }}" class="btn btn-sm btn-outline-primary">
                    View Parent
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
