@extends('layouts.admin')
@section('title', 'Add Gallery Item')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Add Gallery Item</h1>
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.gallery.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select Type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category') }}">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title (English) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title[en]" value="{{ old('title.en') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="title_bn" class="form-label">Title (Bengali)</label>
                <input type="text" class="form-control" id="title_bn" name="title[bn]" value="{{ old('title.bn') }}">
            </div>
            <div class="mb-3">
                <label for="title_ar" class="form-label">Title (Arabic)</label>
                <input type="text" class="form-control" id="title_ar" name="title[ar]" value="{{ old('title.ar') }}" dir="rtl">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description (English)</label>
                <textarea class="form-control" id="description" name="description[en]" rows="3">{{ old('description.en') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="description_bn" class="form-label">Description (Bengali)</label>
                <textarea class="form-control" id="description_bn" name="description[bn]" rows="3">{{ old('description.bn') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="description_ar" class="form-label">Description (Arabic)</label>
                <textarea class="form-control" id="description_ar" name="description[ar]" rows="3" dir="rtl">{{ old('description.ar') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="image" name="image" value="{{ old('image') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="video_url" class="form-label">Video URL (for video type)</label>
                        <input type="url" class="form-control" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Featured</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Gallery Item
                </button>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
