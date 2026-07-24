@extends('layouts.admin')
@section('title', 'Edit Notice')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Edit Notice</h1>
    <a href="{{ route('admin.notices.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.notices.update', $notice->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select Type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $notice->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <input type="number" class="form-control" id="priority" name="priority" value="{{ old('priority', $notice->priority ?? 0) }}">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content (English) <span class="text-danger">*</span></label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content[en]" rows="3" required>{{ old('content.en', $notice->content['en'] ?? '') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="content_bn" class="form-label">Content (Bengali)</label>
                <textarea class="form-control" id="content_bn" name="content[bn]" rows="3">{{ old('content.bn', $notice->content['bn'] ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="content_ar" class="form-label">Content (Arabic)</label>
                <textarea class="form-control" id="content_ar" name="content[ar]" rows="3" dir="rtl">{{ old('content.ar', $notice->content['ar'] ?? '') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL</label>
                        <input type="url" class="form-control" id="link_url" name="link_url" value="{{ old('link_url', $notice->link_url) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="link_text_en" class="form-label">Link Text (English)</label>
                        <input type="text" class="form-control" id="link_text_en" name="link_text[en]" value="{{ old('link_text.en', $notice->link_text['en'] ?? '') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="link_text_bn" class="form-label">Link Text (Bengali)</label>
                        <input type="text" class="form-control" id="link_text_bn" name="link_text[bn]" value="{{ old('link_text.bn', $notice->link_text['bn'] ?? '') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $notice->start_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $notice->end_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $notice->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Notice
                </button>
                <a href="{{ route('admin.notices.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
