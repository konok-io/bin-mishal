@extends('layouts.admin')
@section('title', 'Add Page')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Add Page</h1>
    <a href="{{ route('pages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('pages.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title_en" class="form-label">Title (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title.en') is-invalid @enderror" id="title_en" name="title[en]" value="{{ old('title.en') }}" required>
                        @error('title.en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title_bn" class="form-label">Title (Bengali)</label>
                        <input type="text" class="form-control" id="title_bn" name="title[bn]" value="{{ old('title.bn') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug_en" class="form-label">Slug (English)</label>
                        <input type="text" class="form-control" id="slug_en" name="slug[en]" value="{{ old('slug.en') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug_bn" class="form-label">Slug (Bengali)</label>
                        <input type="text" class="form-control" id="slug_bn" name="slug[bn]" value="{{ old('slug.bn') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="template" class="form-label">Template <span class="text-danger">*</span></label>
                        <select class="form-select @error('template') is-invalid @enderror" id="template" name="template" required>
                            <option value="">Select Template</option>
                            @foreach($templates as $key => $label)
                                <option value="{{ $key }}" {{ old('template') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Page</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">None (Top Level)</option>
                            @foreach($parentPages as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->title['en'] ?? 'Untitled' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="is_homepage" name="is_homepage" value="1" {{ old('is_homepage') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_homepage">Set as Homepage</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="show_header" name="show_header" value="1" {{ old('show_header', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_header">Show Header</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="show_footer" name="show_footer" value="1" {{ old('show_footer', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_footer">Show Footer</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Page
                </button>
                <a href="{{ route('pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
