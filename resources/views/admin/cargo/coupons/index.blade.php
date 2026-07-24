@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Discount Coupons</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Coupon
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Discount</th>
                            <th>Min Order</th>
                            <th>Usage</th>
                            <th>Valid Period</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td><strong>{{ $coupon->code }}</strong></td>
                            <td>{{ $coupon->name }}</td>
                            <td>
                                @if($coupon->discount_type == 'percentage')
                                    {{ $coupon->discount_value }}% @if($coupon->max_discount)<small>(Max SAR {{ $coupon->max_discount }})</small>@endif
                                @else
                                    SAR {{ number_format($coupon->discount_value, 2) }}
                                @endif
                            </td>
                            <td>SAR {{ number_format($coupon->min_order_amount, 2) }}</td>
                            <td>{{ $coupon->used_count }} @if($coupon->usage_limit)/ {{ $coupon->usage_limit }}@endif</td>
                            <td>{{ $coupon->valid_from->format('d M') }} - {{ $coupon->valid_until->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $coupon->is_active ? ($coupon->isValid() ? 'success' : 'warning') : 'danger' }}">
                                    {{ $coupon->is_active ? ($coupon->isValid() ? 'Active' : 'Expired') : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $coupon->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.cargo.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $coupon->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.cargo.coupons.update', $coupon->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit Coupon</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required></div>
                                                <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ $coupon->name }}" required></div>
                                                <div class="col-md-6"><label class="form-label">Discount Type</label><select name="discount_type" class="form-select"><option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option><option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option></select></div>
                                                <div class="col-md-6"><label class="form-label">Discount Value *</label><input type="number" name="discount_value" class="form-control" value="{{ $coupon->discount_value }}" required></div>
                                                <div class="col-md-6"><label class="form-label">Min Order Amount</label><input type="number" name="min_order_amount" class="form-control" value="{{ $coupon->min_order_amount }}"></div>
                                                <div class="col-md-6"><label class="form-label">Max Discount</label><input type="number" name="max_discount" class="form-control" value="{{ $coupon->max_discount }}"></div>
                                                <div class="col-md-6"><label class="form-label">Usage Limit</label><input type="number" name="usage_limit" class="form-control" value="{{ $coupon->usage_limit }}"></div>
                                                <div class="col-md-3"><label class="form-label">Valid From</label><input type="date" name="valid_from" class="form-control" value="{{ $coupon->valid_from->format('Y-m-d') }}"></div>
                                                <div class="col-md-3"><label class="form-label">Valid Until</label><input type="date" name="valid_until" class="form-control" value="{{ $coupon->valid_until->format('Y-m-d') }}"></div>
                                                <div class="col-md-12"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Inactive</option></select></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="8" class="text-center">No coupons found</td></tr>
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
            <form action="{{ route('admin.cargo.coupons.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Coupon</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" required placeholder="e.g., SAVE20"></div>
                        <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Discount Type</label><select name="discount_type" class="form-select"><option value="percentage">Percentage</option><option value="fixed">Fixed Amount</option></select></div>
                        <div class="col-md-6"><label class="form-label">Discount Value *</label><input type="number" name="discount_value" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Min Order Amount</label><input type="number" name="min_order_amount" class="form-control" value="0"></div>
                        <div class="col-md-6"><label class="form-label">Max Discount</label><input type="number" name="max_discount" class="form-control" placeholder="For percentage only"></div>
                        <div class="col-md-6"><label class="form-label">Usage Limit</label><input type="number" name="usage_limit" class="form-control" placeholder="Leave empty for unlimited"></div>
                        <div class="col-md-3"><label class="form-label">Valid From</label><input type="date" name="valid_from" class="form-control" value="{{ date('Y-m-d') }}"></div>
                        <div class="col-md-3"><label class="form-label">Valid Until</label><input type="date" name="valid_until" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}"></div>
                        <div class="col-md-12"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Create</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
