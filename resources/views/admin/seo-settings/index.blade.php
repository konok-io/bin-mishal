@extends('layouts.admin')
@section('title', 'SEO Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-search"></i> SEO Settings</h1>
    <a href="{{ route('admin.seo-settings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Setting
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.seo-settings.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search settings..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="page" class="form-select">
                    <option value="">All Pages</option>
                    @foreach($pages as $page)
                        <option value="{{ $page }}" {{ request('page') == $page ? 'selected' : '' }}>{{ $page }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.seo-settings.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Meta Title</th>
                        <th>Meta Keywords</th>
                        <th>OG Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seoSettings as $setting)
                    <tr>
                        <td><strong>{{ $setting->page }}</strong></td>
                        <td>{{ Str::limit($setting->meta_title, 40) ?: '-' }}</td>
                        <td>{{ Str::limit($setting->meta_keywords, 40) ?: '-' }}</td>
                        <td>{{ Str::limit($setting->og_title, 40) ?: '-' }}</td>
                        <td>
                            <a href="{{ route('admin.seo-settings.edit', $setting->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.seo-settings.destroy', $setting->id) }}" method="POST" class="d-inline">
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
                        <td colspan="5" class="text-center text-muted py-4">No SEO settings found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $seoSettings->withQueryString()->links() }}
    </div>
</div>
@endsection
