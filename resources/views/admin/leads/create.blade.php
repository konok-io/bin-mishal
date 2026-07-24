@extends('layouts.admin')
@section('title', 'New Lead')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-plus"></i> New Lead</h1>
    <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.leads.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Interest</label>
                    <input type="text" name="service_interest" class="form-control" value="{{ old('service_interest') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Source</label>
                    <input type="text" name="source" class="form-control" value="{{ old('source') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Follow Up Date</label>
                    <input type="date" name="follow_up_date" class="form-control" value="{{ old('follow_up_date') }}">
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Save Lead
            </button>
        </form>
    </div>
</div>
@endsection
