@extends('layouts.admin')
@section('title', 'Social Links')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-share"></i> Social Links</h1>
    <a href="{{ route('admin.social-links.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Link
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.social-links.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search links..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.social-links.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Platform</th>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($socialLinks as $link)
                    <tr>
                        <td>
                            @if($link->icon)
                                <i class="{{ $link->icon }}"></i>
                            @endif
                            {{ $link->platform ?? '-' }}
                        </td>
                        <td><strong>{{ $link->name }}</strong></td>
                        <td><a href="{{ $link->url }}" target="_blank">{{ Str::limit($link->url, 40) }}</a></td>
                        <td>
                            @if($link->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.social-links.edit', $link->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.social-links.destroy', $link->id) }}" method="POST" class="d-inline">
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
                        <td colspan="5" class="text-center text-muted py-4">No social links found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $socialLinks->withQueryString()->links() }}
    </div>
</div>
@endsection
