@extends('layouts.admin')
@section('title', 'View Audit Log')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Audit Log Details</h1>
    <div class="d-flex gap-2">
        <form action="{{ route('audit-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this log?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
        <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-muted mb-3">Log Information</h5>
                <div class="mb-2">
                    <strong>Action:</strong> 
                    <span class="badge bg-{{ $log->action == 'created' ? 'success' : ($log->action == 'deleted' ? 'danger' : 'info') }}">
                        {{ ucfirst($log->action) }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Model Type:</strong> {{ $log->model_type ?? 'N/A' }}
                </div>
                <div class="mb-2">
                    <strong>Model ID:</strong> {{ $log->model_id ?? 'N/A' }}
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="text-muted mb-3">User Information</h5>
                <div class="mb-2">
                    <strong>User:</strong> {{ $log->user ? $log->user->name : 'System' }}
                </div>
                <div class="mb-2">
                    <strong>IP Address:</strong> {{ $log->ip_address ?? 'N/A' }}
                </div>
                <div class="mb-2">
                    <strong>Timestamp:</strong> {{ $log->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>

        @if($log->user_agent)
        <hr>
        <h5 class="text-muted mb-3">User Agent</h5>
        <div class="p-3 bg-light rounded">
            <code>{{ $log->user_agent }}</code>
        </div>
        @endif

        @if($log->properties)
        <hr>
        <h5 class="text-muted mb-3">Changes</h5>
        <div class="p-3 bg-light rounded">
            <pre class="mb-0">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Logs
            </a>
        </div>
    </div>
</div>
@endsection
