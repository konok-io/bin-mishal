@extends('layouts.admin')
@section('title', 'New Visa Application')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-passport"></i> New Visa Application</h1>
    <a href="{{ route('admin.visas.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.visas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->user->name ?? 'N/A' }}
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Visa Type *</label>
                    <select name="visa_type_id" class="form-select @error('visa_type_id') is-invalid @enderror" required>
                        <option value="">Select Visa Type</option>
                        @foreach($visaTypes as $type)
                        <option value="{{ $type->id }}" {{ old('visa_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} - SAR {{ number_format($type->total_fee, 2) }}
                        </option>
                        @endforeach
                    </select>
                    @error('visa_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Applicant Name</label>
                    <input type="text" name="applicant_name" class="form-control" value="{{ old('applicant_name') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Passport No</label>
                    <input type="text" name="passport_no" class="form-control" value="{{ old('passport_no') }}">
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Create Application
            </button>
        </form>
    </div>
</div>
@endsection
