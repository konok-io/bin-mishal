@extends('layouts.admin')
@section('title', 'SEO Settings')

@section('content')
@php
    $pages = \App\Models\SeoSetting::PAGES;
@endphp
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">SEO Settings</h1>
    <a href="{{ route('admin.seo-settings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add SEO Setting
    </a>
</div>

<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All SEO Settings</h5>
        <span class="badge bg-primary">{{ $settings->total() }} Total</span>
    </div>
    <div class="card-body">
        {{-- Filters --}}
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="page" class="form-select">
                        <option value="">All Pages</option>
                        @foreach($pages as $key => $page)
                            <option value="{{ $key }}" {{ request('page') == $key ? 'selected' : '' }}>
                                {{ $page }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="locale" class="form-select">
                        <option value="">All Locales</option>
                        <option value="en" {{ request('locale') == 'en' ? 'selected' : '' }}>English</option>
                        <option value="bn" {{ request('locale') == 'bn' ? 'selected' : '' }}>Bengali</option>
                        <option value="ar" {{ request('locale') == 'ar' ? 'selected' : '' }}>Arabic</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.seo-settings.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                </div>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Page</th>
                        <th>Locale</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $setting)
                    <tr>
                        <td>
                            <span class="badge bg-info">{{ $pages[$setting->page] ?? $setting->page }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ strtoupper($setting->locale) }}</span>
                        </td>
                        <td>
                            {{ $setting->meta_title ?? 'N/A' }}
                        </td>
                        <td>
                            {{ Str::limit($setting->meta_description, 80) ?? 'N/A' }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $setting->is_active ? 'success' : 'danger' }}">
                                {{ $setting->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.seo-settings.edit', $setting->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.seo-settings.destroy', $setting->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-1 d-block mb-2"></i>
                            No SEO settings found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $settings->withQueryString()->links() }}
    </div>
</div>
@endsection
