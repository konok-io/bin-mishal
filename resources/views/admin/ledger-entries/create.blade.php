@extends('layouts.admin')
@section('title', 'Add Ledger Entry')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-journal-text"></i> Add Ledger Entry</h1>
    <a href="{{ route('ledger-entries.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('ledger-entries.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="account_id" class="form-label">Account</label>
                    <select name="account_id" id="account_id" class="form-select @error('account_id') is-invalid @enderror" required>
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->name }} ({{ ucfirst($account->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('account_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="entry_type" class="form-label">Entry Type</label>
                    <select name="entry_type" id="entry_type" class="form-select @error('entry_type') is-invalid @enderror" required>
                        @foreach($entryTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('entry_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('entry_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" step="0.01" min="0.01" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="entry_date" class="form-label">Entry Date</label>
                    <input type="date" name="entry_date" id="entry_date" class="form-control @error('entry_date') is-invalid @enderror" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                    @error('entry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="reference" class="form-label">Reference</label>
                    <input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference') }}" placeholder="Optional reference number">
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('ledger-entries.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Entry</button>
            </div>
        </form>
    </div>
</div>
@endsection
