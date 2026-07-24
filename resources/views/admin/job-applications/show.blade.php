@extends('layouts.admin')
@section('title', 'Application Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> {{ $jobApplication->full_name }}</h1>
    <a href="{{ route('admin.job-applications.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Application Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Job Position:</strong> {{ $jobApplication->job->title ?? 'N/A' }}</p>
                        <p><strong>Full Name:</strong> {{ $jobApplication->full_name }}</p>
                        <p><strong>Email:</strong> <a href="mailto:{{ $jobApplication->email }}">{{ $jobApplication->email }}</a></p>
                        <p><strong>Phone:</strong> {{ $jobApplication->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong>
                            @php
                                $statusClass = match($jobApplication->status) {
                                    'new' => 'info',
                                    'screening' => 'primary',
                                    'interview' => 'warning',
                                    'offer' => 'success',
                                    'rejected' => 'danger',
                                    'withdrawn' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statuses[$jobApplication->status] ?? $jobApplication->status }}</span>
                        </p>
                        <p><strong>Applied:</strong> {{ $jobApplication->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <hr>
                <p><strong>Links:</strong></p>
                <p>
                    @if($jobApplication->resume_path)
                        <a href="{{ $jobApplication->resume_path }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-file-earmark"></i> Resume
                        </a>
                    @endif
                    @if($jobApplication->portfolio_url)
                        <a href="{{ $jobApplication->portfolio_url }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-globe"></i> Portfolio
                        </a>
                    @endif
                    @if($jobApplication->linkedin_url)
                        <a href="{{ $jobApplication->linkedin_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-linkedin"></i> LinkedIn
                        </a>
                    @endif
                </p>
                <hr>
                <p><strong>Cover Letter:</strong></p>
                <p>{!! nl2br(e($jobApplication->cover_letter ?? 'No cover letter provided')) !!}</p>
                @if($jobApplication->notes)
                <hr>
                <p><strong>Admin Notes:</strong></p>
                <p>{!! nl2br(e($jobApplication->notes)) !!}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.job-applications.edit', $jobApplication->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Application
                </a>
                <form action="{{ route('admin.job-applications.destroy', $jobApplication->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this application?')">
                        <i class="bi bi-trash"></i> Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
