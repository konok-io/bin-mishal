@extends('layouts.admin')
@section('title', 'Chart of Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-book"></i> Chart of Accounts</h1>
    <div>
        <a href="{{ route('chart-of-accounts.initialize') }}" class="btn btn-outline-primary" onclick="return confirm('Initialize system accounts?')">
            <i class="bi bi-shield-check"></i> Initialize System Accounts
        </a>
        <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Account
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('chart-of-accounts.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by code or name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}" {{ request('category') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Normal Balance</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    <tr>
                        <td><strong>{{ $account->code }}</strong></td>
                        <td>
                            {{ $account->name }}
                            @if($account->is_system)
                                <span class="badge bg-info ms-1">System</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $typeColors = [
                                    'asset' => 'primary',
                                    'liability' => 'danger',
                                    'equity' => 'success',
                                    'revenue' => 'info',
                                    'expense' => 'warning'
                                ];
                            @endphp
                            <span class="badge bg-{{ $typeColors[$account->type] ?? 'secondary' }}">{{ ucfirst($account->type) }}</span>
                        </td>
                        <td>{{ $account->category ? ucwords(str_replace('_', ' ', $account->category)) : '-' }}</td>
                        <td>{{ ucfirst($account->normal_balance) }}</td>
                        <td>{{ number_format($account->balance, 2) }}</td>
                        <td>
                            @if($account->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('chart-of-accounts.show', $account->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('chart-of-accounts.edit', $account->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(!$account->is_system)
                                <form action="{{ route('chart-of-accounts.destroy', $account->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No accounts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $accounts->withQueryString()->links() }}
    </div>
</div>
@endsection
