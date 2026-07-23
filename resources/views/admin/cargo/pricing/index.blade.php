@extends('admin.layouts.app')

@section('title', 'Cargo Pricing')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cargo Pricing</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Pricing
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Type</th>
                            <th>Weight Range</th>
                            <th>Price/kg</th>
                            <th>Base Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pricings as $pricing)
                        <tr>
                            <td>{{ $pricing->cargoType?->name ?? 'All' }}</td>
                            <td>{{ $pricing->originCity?->name ?? 'All' }}</td>
                            <td>{{ $pricing->destinationCity?->name ?? 'All' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($pricing->pricing_type) }}</span></td>
                            <td>{{ $pricing->min_weight }} - {{ $pricing->max_weight }} kg</td>
                            <td>SAR {{ number_format($pricing->price_per_kg, 2) }}</td>
                            <td>SAR {{ number_format($pricing->base_price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $pricing->is_active ? 'success' : 'danger' }}">
                                    {{ $pricing->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $pricing->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.cargo.pricing.destroy', $pricing->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $pricing->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.cargo.pricing.update', $pricing->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit Pricing</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6"><label class="form-label">Cargo Type</label><select name="cargo_type_id" class="form-select"><option value="">All Types</option>@foreach($cargoTypes as $t)<option value="{{ $t->id }}" {{ $pricing->cargo_type_id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>@endforeach</select></div>
                                                <div class="col-md-6"><label class="form-label">Pricing Type</label><select name="pricing_type" class="form-select"><option value="weight" {{ $pricing->pricing_type == 'weight' ? 'selected' : '' }}>Weight Based</option><option value="package" {{ $pricing->pricing_type == 'package' ? 'selected' : '' }}>Package Based</option><option value="volumetric" {{ $pricing->pricing_type == 'volumetric' ? 'selected' : '' }}>Volumetric</option></select></div>
                                                <div class="col-md-6"><label class="form-label">Min Weight (kg)</label><input type="number" name="min_weight" class="form-control" value="{{ $pricing->min_weight }}"></div>
                                                <div class="col-md-6"><label class="form-label">Max Weight (kg)</label><input type="number" name="max_weight" class="form-control" value="{{ $pricing->max_weight }}"></div>
                                                <div class="col-md-6"><label class="form-label">Price per kg</label><input type="number" name="price_per_kg" class="form-control" value="{{ $pricing->price_per_kg }}"></div>
                                                <div class="col-md-6"><label class="form-label">Base Price</label><input type="number" name="base_price" class="form-control" value="{{ $pricing->base_price }}"></div>
                                                <div class="col-md-6"><label class="form-label">VAT %</label><input type="number" name="vat_percentage" class="form-control" value="{{ $pricing->vat_percentage }}"></div>
                                                <div class="col-md-6"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1" {{ $pricing->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$pricing->is_active ? 'selected' : '' }}>Inactive</option></select></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="9" class="text-center">No pricing found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.cargo.pricing.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Pricing</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Cargo Type</label><select name="cargo_type_id" class="form-select"><option value="">All Types</option>@foreach($cargoTypes as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select></div>
                        <div class="col-md-6"><label class="form-label">Pricing Type</label><select name="pricing_type" class="form-select"><option value="weight">Weight Based</option><option value="package">Package Based</option><option value="volumetric">Volumetric</option></select></div>
                        <div class="col-md-6"><label class="form-label">Min Weight (kg)</label><input type="number" name="min_weight" class="form-control" value="0"></div>
                        <div class="col-md-6"><label class="form-label">Max Weight (kg)</label><input type="number" name="max_weight" class="form-control" value="100"></div>
                        <div class="col-md-6"><label class="form-label">Price per kg</label><input type="number" name="price_per_kg" class="form-control" value="0"></div>
                        <div class="col-md-6"><label class="form-label">Base Price</label><input type="number" name="base_price" class="form-control" value="0"></div>
                        <div class="col-md-6"><label class="form-label">VAT %</label><input type="number" name="vat_percentage" class="form-control" value="15"></div>
                        <div class="col-md-6"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Create</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
