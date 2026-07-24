@extends('layouts.admin')
@section('title', 'Job Applications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> Job Applications</h1>
    <a href="{{ route('job-applications.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Application
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('job-applications.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search name, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="job_id" class="form-select">
                    <option value="">All Jobs</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
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
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('job-applications.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Job</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr>
                        <td><strong>{{ $application->full_name }}</strong></td>
                        <td>{{ $application->email }}</td>
                        <td>{{ $application->phone ?? 'N/A' }}</td>
                        <td>{{ Str::limit($application->job->title ?? 'N/A', 25) }}</td>
                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                        <td>
                            @php
                                $statusClass = match($application->status) {
                                    'new' => 'info',
                                    'screening' => 'primary',
                                    'interview' => 'warning',
                                    'offer' => 'success',
                                    'rejected' => 'danger',
                                    'withdrawn' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statuses[$application->status] ?? $application->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('job-applications.show', $application->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('job-applications.edit', $application->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('job-applications.destroy', $application->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-4">No applications found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $applications->withQueryString()->links() }}
    </div>
</div>
@endsection
