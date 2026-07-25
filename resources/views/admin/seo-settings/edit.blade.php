@extends('layouts.admin')
@section('title', 'Edit SEO Setting')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Edit SEO Setting</h1>
    <a href="{{ route('admin.seo-settings.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.seo-settings.update', $setting->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Basic Information</h6>
                    <div class="mb-3">
                        <label for="page" class="form-label">Page <span class="text-danger">*</span></label>
                        <select class="form-select @error('page') is-invalid @enderror" id="page" name="page" required>
                            <option value="">Select Page</option>
                            @foreach($pages as $key => $page)
                                <option value="{{ $key }}" {{ old('page', $setting->page) == $key ? 'selected' : '' }}>
                                    {{ $page }}
                                </option>
                            @endforeach
                        </select>
                        @error('page')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="locale" class="form-label">Locale <span class="text-danger">*</span></label>
                        <select class="form-select @error('locale') is-invalid @enderror" id="locale" name="locale" required>
                            <option value="">Select Locale</option>
                            @foreach($locales as $locale)
                                <option value="{{ $locale }}" {{ old('locale', $setting->locale) == $locale ? 'selected' : '' }}>
                                    {{ strtoupper($locale) }}
                                </option>
                            @endforeach
                        </select>
                        @error('locale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $setting->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Meta Tags</h6>
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}" maxlength="255">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3" maxlength="500">{{ old('meta_description', $setting->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $setting->meta_keywords) }}" placeholder="keyword1, keyword2, keyword3">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Open Graph Tags</h6>
                    <div class="mb-3">
                        <label for="og_title" class="form-label">OG Title</label>
                        <input type="text" class="form-control @error('og_title') is-invalid @enderror" id="og_title" name="og_title" value="{{ old('og_title', $setting->og_title) }}" maxlength="255">
                        @error('og_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="og_description" class="form-label">OG Description</label>
                        <textarea class="form-control @error('og_description') is-invalid @enderror" id="og_description" name="og_description" rows="2" maxlength="500">{{ old('og_description', $setting->og_description) }}</textarea>
                        @error('og_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="og_image" class="form-label">OG Image URL</label>
                        <input type="url" class="form-control @error('og_image') is-invalid @enderror" id="og_image" name="og_image" value="{{ old('og_image', $setting->og_image) }}">
                        @error('og_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Advanced Settings</h6>
                    <div class="mb-3">
                        <label for="canonical_url" class="form-label">Canonical URL</label>
                        <input type="url" class="form-control @error('canonical_url') is-invalid @enderror" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $setting->canonical_url) }}">
                        @error('canonical_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="robots" class="form-label">Robots</label>
                        <select class="form-select @error('robots') is-invalid @enderror" id="robots" name="robots">
                            <option value="index, follow" {{ old('robots', $setting->robots) == 'index, follow' ? 'selected' : '' }}>index, follow</option>
                            <option value="noindex, follow" {{ old('robots', $setting->robots) == 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                            <option value="index, nofollow" {{ old('robots', $setting->robots) == 'index, nofollow' ? 'selected' : '' }}>index, nofollow</option>
                            <option value="noindex, nofollow" {{ old('robots', $setting->robots) == 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                        </select>
                        @error('robots')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="schema_markup" class="form-label">Schema Markup (JSON-LD)</label>
                        <textarea class="form-control @error('schema_markup') is-invalid @enderror" id="schema_markup" name="schema_markup" rows="4">{{ old('schema_markup', $setting->schema_markup) }}</textarea>
                        @error('schema_markup')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update SEO Setting
                </button>
                <a href="{{ route('admin.seo-settings.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
