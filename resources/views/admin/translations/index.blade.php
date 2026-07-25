@extends('layouts.admin')
@section('title', 'Translations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-translate"></i> Translations</h1>
    <div>
        <a href="{{ route('admin.translations.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>
        <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Translation
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.translations.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by key or value..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="group" class="form-select">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                    <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Key</th>
                        <th>English</th>
                        <th>Bengali</th>
                        <th>Arabic</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($translations as $translation)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $translation->group }}</span></td>
                        <td><code>{{ Str::limit($translation->key, 30) }}</code></td>
                        <td>{{ Str::limit($translation->value_en, 40) ?: '<span class="text-muted">-</span>' }}</td>
                        <td>{{ Str::limit($translation->value_bn, 40) ?: '<span class="text-muted">-</span>' }}</td>
                        <td>{{ Str::limit($translation->value_ar, 40) ?: '<span class="text-muted">-</span>' }}</td>
                        <td>
                            @if($translation->status === 'complete')
                                <span class="badge bg-success">Complete</span>
                            @else
                                <span class="badge bg-warning">Incomplete</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.translations.edit', $translation->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.translations.destroy', $translation->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-4">No translations found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $translations->withQueryString()->links() }}
    </div>
</div>
@endsection
