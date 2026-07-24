@extends('layouts.admin')
@section('title', 'Job Postings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-briefcase"></i> Job Postings</h1>
    <a href="{{ route('job-postings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Job
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('job-postings.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($jobTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('job-postings.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Salary Range</th>
                        <th>Status</th>
                        <th>Applications</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr>
                        <td>
                            <strong>{{ $job->title }}</strong>
                            @if($job->is_featured)
                                <span class="badge bg-warning ms-1">Featured</span>
                            @endif
                        </td>
                        <td>{{ $job->department->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-secondary">{{ $jobTypes[$job->job_type] ?? $job->job_type }}</span></td>
                        <td>{{ $job->location ?? 'N/A' }}</td>
                        <td>
                            @if($job->salary_min && $job->salary_max)
                                {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $job->applications()->count() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('job-postings.show', $job->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('job-postings.edit', $job->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('job-postings.destroy', $job->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No jobs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $jobs->withQueryString()->links() }}
    </div>
</div>
@endsection
