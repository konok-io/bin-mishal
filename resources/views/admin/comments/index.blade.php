@extends('layouts.admin')
@section('title', 'Comments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-chat-left-text"></i> Comments</h1>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.comments.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search comments..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Author</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                    <tr>
                        <td>
                            @if($comment->post)
                                <a href="{{ route('blog.detail', $comment->post->slug) }}" target="_blank">{{ Str::limit($comment->post->title, 30) }}</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $comment->name }}</strong><br>
                            <small class="text-muted">{{ $comment->email }}</small>
                        </td>
                        <td>{{ Str::limit($comment->comment, 80) }}</td>
                        <td>
                            @if($comment->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $comment->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_approved" value="{{ $comment->is_approved ? 0 : 1 }}">
                                <button type="submit" class="btn btn-sm btn-{{ $comment->is_approved ? 'warning' : 'success' }}" title="{{ $comment->is_approved ? 'Disapprove' : 'Approve' }}">
                                    <i class="bi bi-{{ $comment->is_approved ? 'x-circle' : 'check-circle' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline">
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
                        <td colspan="6" class="text-center text-muted py-4">No comments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $comments->withQueryString()->links() }}
    </div>
</div>
@endsection
