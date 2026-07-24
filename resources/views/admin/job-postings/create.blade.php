@extends('layouts.admin')
@section('title', 'Add Job Posting')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-briefcase"></i> Add Job Posting</h1>
    <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('job-postings.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Job Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">No Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements</label>
                <textarea name="requirements" id="requirements" class="form-control" rows="3">{{ old('requirements') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="responsibilities" class="form-label">Responsibilities</label>
                <textarea name="responsibilities" id="responsibilities" class="form-control" rows="3">{{ old('responsibilities') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="job_type" class="form-label">Job Type</label>
                    <select name="job_type" id="job_type" class="form-select @error('job_type') is-invalid @enderror" required>
                        @foreach($jobTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('job_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="experience_level" class="form-label">Experience Level</label>
                    <select name="experience_level" id="experience_level" class="form-select">
                        <option value="">Any Level</option>
                        @foreach($experiences as $exp)
                            <option value="{{ $exp }}" {{ old('experience_level') == $exp ? 'selected' : '' }}>{{ $exp }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="salary_min" class="form-label">Min Salary</label>
                    <input type="number" name="salary_min" id="salary_min" class="form-control" value="{{ old('salary_min') }}" min="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="salary_max" class="form-label">Max Salary</label>
                    <input type="number" name="salary_max" id="salary_max" class="form-control" value="{{ old('salary_max') }}" min="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="vacancies" class="form-label">Vacancies</label>
                    <input type="number" name="vacancies" id="vacancies" class="form-control" value="{{ old('vacancies', 1) }}" min="1">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="application_deadline" class="form-label">Deadline</label>
                    <input type="date" name="application_deadline" id="application_deadline" class="form-control" value="{{ old('application_deadline') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', 'draft') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-check mt-4 pt-3">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Active</label>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-check mt-4 pt-3">
                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured" class="form-check-label">Featured</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('job-postings.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Job</button>
            </div>
        </form>
    </div>
</div>
@endsection
