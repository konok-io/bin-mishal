@extends('layouts.admin')
@section('title', 'Ledger Entries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-journal-text"></i> Ledger Entries</h1>
    <a href="{{ route('ledger-entries.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Entry
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('ledger-entries.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search description or reference..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="account_id" class="form-select">
                    <option value="">All Accounts</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="entry_type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($entryTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('entry_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('ledger-entries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Account</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Reference</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                        <td>
                            <strong>{{ $entry->account->code }}</strong><br>
                            <small class="text-muted">{{ $entry->account->name }}</small>
                        </td>
                        <td>
                            @if($entry->entry_type === 'debit')
                                <span class="badge bg-primary">Debit</span>
                            @else
                                <span class="badge bg-success">Credit</span>
                            @endif
                        </td>
                        <td>{{ number_format($entry->amount, 2) }}</td>
                        <td>{{ Str::limit($entry->description, 40) }}</td>
                        <td>{{ $entry->reference ?? '-' }}</td>
                        <td>
                            <a href="{{ route('ledger-entries.show', $entry->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('ledger-entries.edit', $entry->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(!$entry->is_locked)
                                <form action="{{ route('ledger-entries.destroy', $entry->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-4">No entries found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $entries->withQueryString()->links() }}
    </div>
</div>
@endsection
