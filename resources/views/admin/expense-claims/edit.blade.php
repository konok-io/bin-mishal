@extends('layouts.admin')
@section('title', 'Edit Expense Claim')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-receipt"></i> Edit Expense Claim</h1>
    <a href="{{ route('expense-claims.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('expense-claims.update', $expenseClaim->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">Employee</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $expenseClaim->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $expenseClaim->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $expenseClaim->amount) }}" step="0.01" min="0.01" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $expenseClaim->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="expense_date" class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" id="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', $expenseClaim->expense_date->format('Y-m-d')) }}" required>
                    @error('expense_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $expenseClaim->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="receipt_path" class="form-label">Receipt Path/URL</label>
                    <input type="text" name="receipt_path" id="receipt_path" class="form-control" value="{{ old('receipt_path', $expenseClaim->receipt_path) }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $expenseClaim->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Admin Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes', $expenseClaim->notes) }}</textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('expense-claims.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Claim</button>
            </div>
        </form>
    </div>
</div>
@endsection
