@extends('layouts.admin')
@section('title', 'Edit FAQ')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Edit FAQ</h1>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category', $faq->category) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="service_type" class="form-label">Service Type</label>
                        <input type="text" class="form-control" id="service_type" name="service_type" value="{{ old('service_type', $faq->service_type) }}">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="question" class="form-label">Question (English) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question" value="{{ old('question', $faq->question) }}" required>
                @error('question')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="question_bn" class="form-label">Question (Bengali)</label>
                <input type="text" class="form-control" id="question_bn" name="question_bn" value="{{ old('question_bn', $faq->question_bn) }}">
            </div>
            <div class="mb-3">
                <label for="question_ar" class="form-label">Question (Arabic)</label>
                <input type="text" class="form-control" id="question_ar" name="question_ar" value="{{ old('question_ar', $faq->question_ar) }}" dir="rtl">
            </div>
            <div class="mb-3">
                <label for="answer" class="form-label">Answer (English) <span class="text-danger">*</span></label>
                <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" rows="4" required>{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="answer_bn" class="form-label">Answer (Bengali)</label>
                <textarea class="form-control" id="answer_bn" name="answer_bn" rows="3">{{ old('answer_bn', $faq->answer_bn) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="answer_ar" class="form-label">Answer (Arabic)</label>
                <textarea class="form-control" id="answer_ar" name="answer_ar" rows="3" dir="rtl">{{ old('answer_ar', $faq->answer_ar) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update FAQ
                </button>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
