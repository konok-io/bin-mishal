@extends('layouts.admin')
@section('title', 'Umrah Packages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-building"></i> Umrah Packages</h1>
    <a href="{{ route('admin.umrah.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Package
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Makkah Hotel</th>
                    <th>Madinah Hotel</th>
                    <th>Price (Double)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                <tr>
                    <td>{{ $package->title }}</td>
                    <td>{{ $package->duration_nights }} Nights</td>
                    <td>{{ $package->makkah_hotel ?? '-' }} ({{ $package->makkah_hotel_stars ?? 3 }}★)</td>
                    <td>{{ $package->madinah_hotel ?? '-' }} ({{ $package->madinah_hotel_stars ?? 3 }}★)</td>
                    <td>SAR {{ number_format($package->price_double ?? 0, 2) }}</td>
                    <td>
                        @php
                            $statusClass = $package->status === 'active' ? 'bg-success' : 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($package->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.umrah.show', $package->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.umrah.edit', $package->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No packages found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $packages->withQueryString()->links() }}
    </div>
</div>
@endsection
