@extends('layouts.admin')
@section('title', 'Add Translation')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Add Translation</h1>
    <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.translations.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="group" class="form-label">Group <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('group') is-invalid @enderror" id="group" name="group" value="{{ old('group') }}" required>
                        @error('group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key') }}" required>
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="value_en" class="form-label">English Value <span class="text-danger">*</span></label>
                <textarea class="form-control @error('value_en') is-invalid @enderror" id="value_en" name="value_en" rows="3" required>{{ old('value_en') }}</textarea>
                @error('value_en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="value_bn" class="form-label">Bengali Value</label>
                <textarea class="form-control" id="value_bn" name="value_bn" rows="3">{{ old('value_bn') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="value_ar" class="form-label">Arabic Value</label>
                <textarea class="form-control" id="value_ar" name="value_ar" rows="3" dir="rtl">{{ old('value_ar') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="source" class="form-label">Source</label>
                <input type="text" class="form-control" id="source" name="source" value="{{ old('source') }}">
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Translation
                </button>
                <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
