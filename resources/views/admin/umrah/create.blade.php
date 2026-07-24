@extends('layouts.admin')
@section('title', 'New Package')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-building"></i> New Umrah Package</h1>
    <a href="{{ route('admin.umrah.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.umrah.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Duration (Days)</label>
                    <input type="number" name="duration_days" class="form-control" value="7" min="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Makkah Hotel</label>
                    <input type="text" name="makkah_hotel" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Makkah Stars</label>
                    <select name="makkah_hotel_stars" class="form-select">
                        <option value="3">3 Star</option>
                        <option value="4">4 Star</option>
                        <option value="5">5 Star</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Makkah Nights</label>
                    <input type="number" name="makkah_nights" class="form-control" value="3" min="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Madinah Hotel</label>
                    <input type="text" name="madinah_hotel" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Madinah Stars</label>
                    <select name="madinah_hotel_stars" class="form-select">
                        <option value="3">3 Star</option>
                        <option value="4">4 Star</option>
                        <option value="5">5 Star</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Madinah Nights</label>
                    <input type="number" name="madinah_nights" class="form-control" value="3" min="1">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Price (Double Sharing)</label>
                    <input type="number" name="price_double" class="form-control" step="0.01">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Create Package
            </button>
        </form>
    </div>
</div>
@endsection
