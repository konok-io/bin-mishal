@extends('layouts.admin')
@section('title', 'Customer Registration')

@section('content')
<div class="admin-page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Customer Registration</h1>
        <div class="btn-group">
            <a href="{{ route('admin.customers.registration.scan') }}" class="btn btn-outline-primary">
                <i class="bi bi-qr-code-scan me-1"></i> Scan Registration
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>New Registration</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.customers.registration.store') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name (Arabic)</label>
                            <input type="text" name="name_ar" class="form-control" dir="rtl">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">ID Type <span class="text-danger">*</span></label>
                            <select name="id_type" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="iqama">Iqama</option>
                                <option value="passport">Passport</option>
                                <option value="national_id">National ID</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Number <span class="text-danger">*</span></label>
                            <input type="text" name="id_number" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nationality <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company / Sponsor (Optional)</label>
                        <input type="text" name="company" class="form-control">
                    </div>

                    <hr>
                    <h5 class="mb-3">Services & Pricing</h5>

                    <div id="services-container">
                        <div class="row service-row mb-2">
                            <div class="col-md-8">
                                <input type="text" name="services[]" class="form-control" placeholder="Service name">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="amounts[]" class="form-control" placeholder="Amount (SAR)" step="0.01" min="0">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger remove-service">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-service">
                        <i class="bi bi-plus-lg me-1"></i> Add Service
                    </button>

                    <div class="mb-3">
                        <strong>Total: SAR <span id="total-amount">0.00</span></strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i> Complete Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Instructions</h5>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li class="mb-2">Enter customer details</li>
                    <li class="mb-2">Select ID type and enter ID number</li>
                    <li class="mb-2">Add services with amounts</li>
                    <li class="mb-2">Click Complete to generate PDF</li>
                    <li>Print the registration slip with QR codes</li>
                </ol>
            </div>
        </div>

        <div class="admin-card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-qr-code me-2"></i>ID Scan Option</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Have a scanner? Use the scan option to automatically extract ID information.</p>
                <a href="{{ route('admin.customers.registration.scan') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-upc-scan me-1"></i> Go to Scanner
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('add-service').addEventListener('click', function() {
    const container = document.getElementById('services-container');
    const row = document.createElement('div');
    row.className = 'row service-row mb-2';
    row.innerHTML = `
        <div class="col-md-8">
            <input type="text" name="services[]" class="form-control" placeholder="Service name">
        </div>
        <div class="col-md-3">
            <input type="number" name="amounts[]" class="form-control" placeholder="Amount (SAR)" step="0.01" min="0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger remove-service">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-service')) {
        const rows = document.querySelectorAll('.service-row');
        if (rows.length > 1) {
            e.target.closest('.service-row').remove();
        }
    }
});

document.getElementById('services-container').addEventListener('input', function(e) {
    if (e.target.name === 'amounts[]') {
        let total = 0;
        document.querySelectorAll('input[name="amounts[]"]').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total-amount').textContent = total.toFixed(2);
    }
});
</script>
@endpush
