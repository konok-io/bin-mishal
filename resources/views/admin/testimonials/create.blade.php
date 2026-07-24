@extends('layouts.admin')
@section('title', 'Add Testimonial')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Add Testimonial</h1>
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.testimonials.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name_bn" class="form-label">Name (Bengali)</label>
                        <input type="text" class="form-control" id="name_bn" name="name_bn" value="{{ old('name_bn') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name_ar" class="form-label">Name (Arabic)</label>
                        <input type="text" class="form-control" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" dir="rtl">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1-5)</label>
                        <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating">
                            <option value="">Select Rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="designation" class="form-label">Designation (English)</label>
                        <input type="text" class="form-control" id="designation" name="designation" value="{{ old('designation') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="designation_bn" class="form-label">Designation (Bengali)</label>
                        <input type="text" class="form-control" id="designation_bn" name="designation_bn" value="{{ old('designation_bn') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="designation_ar" class="form-label">Designation (Arabic)</label>
                        <input type="text" class="form-control" id="designation_ar" name="designation_ar" value="{{ old('designation_ar') }}" dir="rtl">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company" class="form-label">Company (English)</label>
                        <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company_bn" class="form-label">Company (Bengali)</label>
                        <input type="text" class="form-control" id="company_bn" name="company_bn" value="{{ old('company_bn') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company_ar" class="form-label">Company (Arabic)</label>
                        <input type="text" class="form-control" id="company_ar" name="company_ar" value="{{ old('company_ar') }}" dir="rtl">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="quote" class="form-label">Quote (English) <span class="text-danger">*</span></label>
                <textarea class="form-control @error('quote') is-invalid @enderror" id="quote" name="quote" rows="4" required>{{ old('quote') }}</textarea>
                @error('quote')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="quote_bn" class="form-label">Quote (Bengali)</label>
                <textarea class="form-control" id="quote_bn" name="quote_bn" rows="3">{{ old('quote_bn') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="quote_ar" class="form-label">Quote (Arabic)</label>
                <textarea class="form-control" id="quote_ar" name="quote_ar" rows="3" dir="rtl">{{ old('quote_ar') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Avatar URL</label>
                        <input type="text" class="form-control" id="avatar" name="avatar" value="{{ old('avatar') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="service_type" class="form-label">Service Type</label>
                        <input type="text" class="form-control" id="service_type" name="service_type" value="{{ old('service_type') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Featured</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Testimonial
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
