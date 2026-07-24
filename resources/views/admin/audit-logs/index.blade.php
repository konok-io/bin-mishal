@extends('layouts.admin')
@section('title', 'Audit Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-clock-history"></i> Audit Logs</h1>
    <a href="{{ route('admin.audit-logs.export') }}" class="btn btn-success">
        <i class="bi bi-download"></i> Export
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search logs..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="model_type" class="form-select">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $model)
                        <option value="{{ $model }}" {{ request('model_type') == $model ? 'selected' : '' }}>{{ class_basename($model) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                        <td>
                            @php
                                $actionClass = match($log->action) {
                                    'create' => 'success',
                                    'update' => 'info',
                                    'delete' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $actionClass }}">{{ ucfirst($log->action) }}</span>
                        </td>
                        <td>
                            @if($log->model_type)
                                <small>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td><small>{{ Str::limit($log->description, 60) }}</small></td>
                        <td><small>{{ $log->ip_address ?? '-' }}</small></td>
                        <td><small>{{ $log->created_at->format('M d, Y H:i') }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No logs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $logs->withQueryString()->links() }}
    </div>
</div>
@endsection
