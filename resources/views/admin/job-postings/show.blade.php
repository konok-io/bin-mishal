@extends('layouts.admin')
@section('title', 'Job Posting Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-briefcase"></i> {{ $job->title }}</h1>
    <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Job Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Department:</strong> {{ $job->department->name ?? 'N/A' }}</p>
                        <p><strong>Job Type:</strong> {{ $jobTypes[$job->job_type] ?? $job->job_type }}</p>
                        <p><strong>Experience Level:</strong> {{ $job->experience_level ?? 'Any' }}</p>
                        <p><strong>Location:</strong> {{ $job->location ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Salary Range:</strong>
                            @if($job->salary_min && $job->salary_max)
                                {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                            @else
                                Not specified
                            @endif
                        </p>
                        <p><strong>Vacancies:</strong> {{ $job->vacancies ?? 1 }}</p>
                        <p><strong>Deadline:</strong> {{ $job->application_deadline?->format('M d, Y') ?? 'No deadline' }}</p>
                        <p><strong>Status:</strong>
                            @php
                                $statusClass = match($job->status) {
                                    'draft' => 'secondary',
                                    'open' => 'success',
                                    'closed' => 'danger',
                                    'on_hold' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statuses[$job->status] ?? $job->status }}</span>
                        </p>
                    </div>
                </div>
                <hr>
                <p><strong>Description:</strong></p>
                <p>{!! nl2br(e($job->description)) !!}</p>
                <hr>
                <p><strong>Requirements:</strong></p>
                <p>{!! nl2br(e($job->requirements ?? 'None specified')) !!}</p>
                <hr>
                <p><strong>Responsibilities:</strong></p>
                <p>{!! nl2br(e($job->responsibilities ?? 'None specified')) !!}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Applications ({{ $applications->count() }})</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($applications as $app)
                        <li class="list-group-item">
                            <a href="{{ route('job-applications.show', $app->id) }}">{{ $app->full_name }}</a>
                            <br><small class="text-muted">{{ $app->email }}</small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No applications yet</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('job-postings.edit', $job->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Job
                </a>
                <form action="{{ route('job-postings.destroy', $job->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this job?')">
                        <i class="bi bi-trash"></i> Delete Job
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
