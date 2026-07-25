@extends('layouts.admin')
@section('title', 'Backup Management')

@section('content')
<div class="admin-page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Backup Management</h1>
        <form method="POST" action="{{ route('admin.settings.backup.create') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-download me-1"></i> Create Backup
            </button>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-database me-2"></i>Database Backups</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($backups->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-folder-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No backups found.</p>
                <p class="small text-muted">Click "Create Backup" to create your first database backup.</p>
            </div>
        @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                    <tr>
                        <td>
                            <i class="bi bi-file-earmark-sql me-1"></i>
                            {{ $backup['filename'] }}
                        </td>
                        <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($backup['created'])->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.settings.backup.download', $backup['filename']) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.settings.backup.delete', $backup['filename']) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this backup?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<div class="alert alert-info mt-4">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Note:</strong> For production environments, consider using automated scheduled backups
    via cron jobs or cloud database services. Backups stored here are local only.
</div>
@endsection
