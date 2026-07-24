@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-envelope"></i> Newsletter Subscribers</h1>
    <a href="{{ route('admin.newsletter-subscribers.export') }}" class="btn btn-success">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.newsletter-subscribers.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="subscribed" {{ request('status') == 'subscribed' ? 'selected' : '' }}>Subscribed</option>
                    <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.newsletter-subscribers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Subscribed Date</th>
                        <th>Unsubscribed Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                    <tr>
                        <td><strong>{{ $subscriber->email }}</strong></td>
                        <td>{{ $subscriber->name ?? '-' }}</td>
                        <td>
                            @if($subscriber->is_subscribed)
                                <span class="badge bg-success">Subscribed</span>
                            @else
                                <span class="badge bg-secondary">Unsubscribed</span>
                            @endif
                        </td>
                        <td>{{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('M d, Y') : '-' }}</td>
                        <td>{{ $subscriber->unsubscribed_at ? $subscriber->unsubscribed_at->format('M d, Y') : '-' }}</td>
                        <td>
                            @if($subscriber->is_subscribed)
                                <form action="{{ route('admin.newsletter-subscribers.unsubscribe', $subscriber->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">Unsubscribe</button>
                                </form>
                            @else
                                <form action="{{ route('admin.newsletter-subscribers.subscribe', $subscriber->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Subscribe</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.newsletter-subscribers.destroy', $subscriber->id) }}" method="POST" class="d-inline">
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
                        <td colspan="6" class="text-center text-muted py-4">No subscribers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $subscribers->withQueryString()->links() }}
    </div>
</div>
@endsection
