@extends('layouts.admin')
@section('title', 'Add Job Application')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> Add Job Application</h1>
    <a href="{{ route('admin.job-applications.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.job-applications.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="job_id" class="form-label">Job Position</label>
                    <select name="job_id" id="job_id" class="form-select @error('job_id') is-invalid @enderror" required>
                        <option value="">Select Job</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ old('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                        @endforeach
                    </select>
                    @error('job_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', 'new') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="resume_path" class="form-label">Resume Path/URL</label>
                    <input type="text" name="resume_path" id="resume_path" class="form-control" value="{{ old('resume_path') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="portfolio_url" class="form-label">Portfolio URL</label>
                    <input type="url" name="portfolio_url" id="portfolio_url" class="form-control" value="{{ old('portfolio_url') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" id="linkedin_url" class="form-control" value="{{ old('linkedin_url') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="cover_letter" class="form-label">Cover Letter</label>
                <textarea name="cover_letter" id="cover_letter" class="form-control" rows="4">{{ old('cover_letter') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Admin Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.job-applications.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Application</button>
            </div>
        </form>
    </div>
</div>
@endsection
