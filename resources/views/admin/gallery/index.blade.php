@extends('layouts.admin')
@section('title', 'Gallery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-images"></i> Gallery</h1>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Image
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.gallery.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search gallery..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($galleryItems as $item)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($item->getFirstMediaUrl())
                        <img src="{{ $item->getFirstMediaUrl('gallery') }}" class="card-img-top" alt="{{ $item->title }}" style="height: 150px; object-fit: cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ Str::limit($item->title, 30) }}</h6>
                        <p class="card-text small text-muted">{{ $item->category ?? '-' }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            @if($item->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                            <div>
                                <a href="{{ route('admin.gallery.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">No gallery items found</div>
            @endforelse
        </div>
    </div>
    <div class="card-footer">
        {{ $galleryItems->withQueryString()->links() }}
    </div>
</div>
@endsection
