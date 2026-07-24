@extends('layouts.admin')
@section('title', 'Expense Claims')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-receipt"></i> Expense Claims</h1>
    <a href="{{ route('expense-claims.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Claim
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('expense-claims.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search title, claim number..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="user_id" class="form-select">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('expense-claims.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Claim #</th>
                        <th>User</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Expense Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                    <tr>
                        <td><strong>{{ $claim->claim_number }}</strong></td>
                        <td>{{ $claim->user->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($claim->title, 30) }}</td>
                        <td>{{ $claim->category ?? 'N/A' }}</td>
                        <td>{{ number_format($claim->amount, 2) }}</td>
                        <td>{{ $claim->expense_date->format('M d, Y') }}</td>
                        <td>
                            @php
                                $statusClass = match($claim->status) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'reimbursed' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statuses[$claim->status] ?? $claim->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('expense-claims.show', $claim->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('expense-claims.edit', $claim->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('expense-claims.destroy', $claim->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No claims found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $claims->withQueryString()->links() }}
    </div>
</div>
@endsection
