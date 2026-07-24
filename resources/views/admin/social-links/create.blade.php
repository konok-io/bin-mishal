@extends('layouts.admin')
@section('title', 'Add Social Link')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Add Social Link</h1>
    <a href="{{ route('admin.social-links.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.social-links.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="platform" class="form-label">Platform <span class="text-danger">*</span></label>
                        <select class="form-select @error('platform') is-invalid @enderror" id="platform" name="platform" required>
                            <option value="">Select Platform</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform }}" {{ old('platform') == $platform ? 'selected' : '' }}>{{ ucfirst($platform) }}</option>
                            @endforeach
                        </select>
                        @error('platform')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon Class (FontAwesome)</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}" placeholder="fab fa-facebook-f">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="name_en" class="form-label">Name (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name.en') is-invalid @enderror" id="name_en" name="name[en]" value="{{ old('name.en') }}" required>
                        @error('name.en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="name_bn" class="form-label">Name (Bengali)</label>
                        <input type="text" class="form-control" id="name_bn" name="name[bn]" value="{{ old('name.bn') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="name_ar" class="form-label">Name (Arabic)</label>
                        <input type="text" class="form-control" id="name_ar" name="name[ar]" value="{{ old('name.ar') }}" dir="rtl">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
                <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" required placeholder="https://...">
                @error('url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}" placeholder="#1877F2">
                        <small class="text-muted">Hex color code (e.g., #1877F2)</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}">
                    </div>
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_visible">Visible</label>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Social Link
                </button>
                <a href="{{ route('admin.social-links.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
