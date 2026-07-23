@extends('layouts.admin')
@section('title', 'Flight Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-airplane"></i> Flight Requests</h1>
    <a href="{{ route('admin.flights.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Request
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Request No</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Date</th>
                    <th>Passengers</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><strong>{{ $req->request_no }}</strong></td>
                    <td>{{ $req->customer->user->name ?? 'N/A' }}</td>
                    <td>{{ $req->fromAirport->iata_code ?? 'N/A' }} → {{ $req->toAirport->iata_code ?? 'N/A' }}</td>
                    <td>{{ $req->departure_date?->format('d M Y') }}</td>
                    <td>{{ $req->adults + $req->children + $req->infants }}</td>
                    <td>
                        @php
                            $statusClass = match($req->status) {
                                'pending' => 'bg-warning',
                                'quoted' => 'bg-info',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($req->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.flights.show', $req->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No requests found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $requests->withQueryString()->links() }}
    </div>
</div>
@endsection
