@extends('layouts.admin')
@section('title', 'Package Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-building"></i> {{ $package->title }}</h1>
    <a href="{{ route('admin.umrah.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Package Details</h5></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Duration:</th><td>{{ $package->duration_nights }} Nights / {{ $package->duration_days }} Days</td></tr>
                    <tr><th>Makkah Hotel:</th><td>{{ $package->makkah_hotel ?? '-' }} ({{ $package->makkah_hotel_stars ?? 3 }}★)</td></tr>
                    <tr><th>Makkah Nights:</th><td>{{ $package->makkah_nights }}</td></tr>
                    <tr><th>Madinah Hotel:</th><td>{{ $package->madinah_hotel ?? '-' }} ({{ $package->madinah_hotel_stars ?? 3 }}★)</td></tr>
                    <tr><th>Madinah Nights:</th><td>{{ $package->madinah_nights }}</td></tr>
                    <tr><th>Transport:</th><td>{{ $package->transport_type ?? '-' }}</td></tr>
                    <tr><th>Meal Plan:</th><td>{{ ucfirst(str_replace('_', ' ', $package->meal_plan ?? 'breakfast')) }}</td></tr>
                    <tr><th>Status:</th><td><span class="badge bg-{{ $package->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($package->status) }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Pricing</h5></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Quad:</th><td>SAR {{ number_format($package->price_quad ?? 0, 2) }}</td></tr>
                    <tr><th>Triple:</th><td>SAR {{ number_format($package->price_triple ?? 0, 2) }}</td></tr>
                    <tr><th>Double:</th><td>SAR {{ number_format($package->price_double ?? 0, 2) }}</td></tr>
                    <tr><th>Single:</th><td>SAR {{ number_format($package->price_single ?? 0, 2) }}</td></tr>
                    <tr><th>Child:</th><td>SAR {{ number_format($package->child_price ?? 0, 2) }}</td></tr>
                    <tr><th>Infant:</th><td>SAR {{ number_format($package->infant_price ?? 0, 2) }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($package->description)
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Description</h5></div>
    <div class="card-body">
        {{ $package->description }}
    </div>
</div>
@endif
@endsection
