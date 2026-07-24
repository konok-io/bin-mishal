@extends('admin.layouts.app')

@section('title', 'New Cargo Booking')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">New Cargo Booking</h1>
        <a href="{{ route('admin.cargo.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.cargo.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Sender Info -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sender Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="sender_name" class="form-control" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="sender_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="sender_email" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">City *</label>
                            <input type="text" name="sender_city" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="sender_address" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receiver Info -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Receiver Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="receiver_name" class="form-control" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="receiver_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="receiver_email" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">City *</label>
                            <input type="text" name="receiver_city" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="receiver_address" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cargo Details -->
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Cargo Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Cargo Type</label>
                                <select name="cargo_type_id" class="form-select">
                                    <option value="">Select Type</option>
                                    @foreach($cargoTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Package Type</label>
                                <select name="cargo_package_id" class="form-select">
                                    <option value="">Select Package</option>
                                    @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }} - SAR {{ $package->base_price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight" class="form-control" step="0.1" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Declared Value (SAR)</label>
                                <input type="number" name="declared_value" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="cargo_description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Special Instructions</label>
                                <textarea name="special_instructions" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Create Booking
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
