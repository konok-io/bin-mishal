@extends('layouts.admin')
@section('title', 'URL Redirect Manager')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">URL Redirect Manager</h1>
</div>

<div class="admin-card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Redirect</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.redirects.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Old URL</label>
                <input type="text" name="old_url" class="form-control" placeholder="/old-page" required>
                <small class="text-muted">The URL to redirect from</small>
            </div>
            <div class="col-md-4">
                <label class="form-label">New URL</label>
                <input type="text" name="new_url" class="form-control" placeholder="/new-page" required>
                <small class="text-muted">The URL to redirect to</small>
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="301">301 - Permanent</option>
                    <option value="302">302 - Temporary</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg me-1"></i> Add
                </button>
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Active Redirects</h5>
        <span class="badge bg-secondary">{{ $redirects->total() }} total</span>
    </div>
    <div class="card-body">
        @if($redirects->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-link-45deg text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No redirects configured.</p>
                <p class="small text-muted">Add redirects above to preserve SEO when pages are moved.</p>
            </div>
        @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Old URL</th>
                        <th>New URL</th>
                        <th>Type</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($redirects as $redirect)
                    <tr>
                        <td>
                            <code>{{ $redirect->old_url }}</code>
                        </td>
                        <td>
                            <code>{{ $redirect->new_url }}</code>
                        </td>
                        <td>
                            <span class="badge bg-{{ $redirect->type == '301' ? 'success' : 'warning' }}">
                                {{ $redirect->type }}
                            </span>
                        </td>
                        <td class="small text-muted">
                            {{ $redirect->notes ?? '-' }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.settings.redirects.delete', $redirect->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this redirect?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $redirects->links() }}
            </div>
        @endif
    </div>
</div>

<div class="alert alert-info mt-4">
    <i class="bi bi-lightbulb me-2"></i>
    <strong>Tips:</strong>
    <ul class="mb-0 mt-2">
        <li>Use <strong>301 redirects</strong> for permanent URL changes (preserves SEO ranking)</li>
        <li>Use <strong>302 redirects</strong> for temporary changes</li>
        <li>Redirects are processed before any other routing</li>
    </ul>
</div>
@endsection
