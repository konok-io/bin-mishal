@extends('layouts.admin')
@section('title', 'Edit SEO Setting')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Edit SEO Setting</h1>
    <a href="{{ route('seo-settings.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('seo-settings.update', $setting->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="page" class="form-label">Page <span class="text-danger">*</span></label>
                        <select class="form-select @error('page') is-invalid @enderror" id="page" name="page" required>
                            <option value="">Select Page</option>
                            @foreach($pages as $key => $label)
                                <option value="{{ $key }}" {{ old('page', $setting->page) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('page')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="locale" class="form-label">Locale <span class="text-danger">*</span></label>
                        <select class="form-select @error('locale') is-invalid @enderror" id="locale" name="locale" required>
                            <option value="">Select Locale</option>
                            @foreach($locales as $locale)
                                <option value="{{ $locale }}" {{ old('locale', $setting->locale) == $locale ? 'selected' : '' }}>{{ strtoupper($locale) }}</option>
                            @endforeach
                        </select>
                        @error('locale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="meta_title" class="form-label">Meta Title</label>
                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}">
                @error('meta_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $setting->meta_description) }}</textarea>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="og_title" class="form-label">OG Title</label>
                        <input type="text" class="form-control" id="og_title" name="og_title" value="{{ old('og_title', $setting->og_title) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="og_image" class="form-label">OG Image URL</label>
                        <input type="text" class="form-control" id="og_image" name="og_image" value="{{ old('og_image', $setting->og_image) }}">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="og_description" class="form-label">OG Description</label>
                <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ old('og_description', $setting->og_description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="canonical_url" class="form-label">Canonical URL</label>
                        <input type="url" class="form-control @error('canonical_url') is-invalid @enderror" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $setting->canonical_url) }}">
                        @error('canonical_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="robots" class="form-label">Robots</label>
                        <select class="form-select" id="robots" name="robots">
                            <option value="index, follow" {{ old('robots', $setting->robots) == 'index, follow' ? 'selected' : '' }}>Index, Follow</option>
                            <option value="noindex, follow" {{ old('robots', $setting->robots) == 'noindex, follow' ? 'selected' : '' }}>Noindex, Follow</option>
                            <option value="index, nofollow" {{ old('robots', $setting->robots) == 'index, nofollow' ? 'selected' : '' }}>Index, Nofollow</option>
                            <option value="noindex, nofollow" {{ old('robots', $setting->robots) == 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="schema_markup" class="form-label">Schema Markup (JSON-LD)</label>
                <textarea class="form-control" id="schema_markup" name="schema_markup" rows="4">{{ old('schema_markup', $setting->schema_markup) }}</textarea>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $setting->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update SEO Setting
                </button>
                <a href="{{ route('seo-settings.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
