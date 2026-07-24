@extends('layouts.admin')
@section('title', 'Edit Account')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-book"></i> Edit Account</h1>
    <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('chart-of-accounts.update', $chartOfAccount->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">Account Code</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $chartOfAccount->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Account Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $chartOfAccount->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $chartOfAccount->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type', $chartOfAccount->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">Select Category</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category', $chartOfAccount->category) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="normal_balance" class="form-label">Normal Balance</label>
                    <select name="normal_balance" id="normal_balance" class="form-select @error('normal_balance') is-invalid @enderror" required>
                        @foreach($normalBalances as $value => $label)
                            <option value="{{ $value }}" {{ old('normal_balance', $chartOfAccount->normal_balance) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('normal_balance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="parent_id" class="form-label">Parent Account</label>
                    <select name="parent_id" id="parent_id" class="form-select">
                        <option value="">None (Top Level)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $chartOfAccount->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} - {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $chartOfAccount->sort_order ?? 0) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="is_active" class="form-label">&nbsp;</label>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $chartOfAccount->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Active</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Account</button>
            </div>
        </form>
    </div>
</div>
@endsection
