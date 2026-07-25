@extends('layouts.admin')
@section('title', 'Scan Registration')

@section('content')
<div class="admin-page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Scan Registration</h1>
        <a href="{{ route('admin.customers.registration.create') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Manual Entry
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-upc-scan me-2"></i>Document Scanner</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Scanner Not Connected</strong><br>
                    <small>To enable ID scanning, please connect a document scanner and configure the SDK in Integrations settings.</small>
                </div>

                <div class="text-center py-5">
                    <div class="scan-placeholder" style="border: 3px dashed #ccc; border-radius: 10px; padding: 60px; max-width: 400px; margin: 0 auto;">
                        <i class="bi bi-upc-scan text-muted" style="font-size: 5rem;"></i>
                        <p class="text-muted mt-3">Scanner preview area</p>
                        <p class="small text-muted">Connect a TWAIN/WIA-compatible scanner to scan documents</p>
                    </div>
                </div>

                <div class="mt-4">
                    <h6>How to use:</h6>
                    <ol>
                        <li>Select document type (Iqama or Passport)</li>
                        <li>Click "Start Scan" to capture the document</li>
                        <li>Review and edit the extracted information</li>
                        <li>Click "Continue" to proceed with registration</li>
                    </ol>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Document Type</label>
                        <select class="form-select" id="scan-doc-type">
                            <option value="iqama">Iqama</option>
                            <option value="passport">Passport</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button class="btn btn-primary w-100" disabled>
                            <i class="bi bi-upc-scan me-1"></i> Start Scan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-qr-code-scan me-2"></i>QR/Barcode Scanner</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Scan a customer's existing QR code to:</p>
                <ul>
                    <li><strong>Verify Payment:</strong> Check payment status</li>
                    <li><strong>Add Service:</strong> Add new services to existing customer</li>
                </ul>

                <div class="text-center py-4">
                    <button class="btn btn-outline-primary" disabled>
                        <i class="bi bi-qr-code-scan me-1"></i> Open Camera Scanner
                    </button>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="manual-code" placeholder="Or enter code manually">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100" onclick="lookupCode()">
                            <i class="bi bi-search me-1"></i> Look Up
                        </button>
                    </div>
                </div>

                <div id="lookup-result" class="mt-3 d-none">
                    <div class="alert alert-success">
                        <h6>Customer Found!</h6>
                        <p class="mb-1" id="lookup-name"></p>
                        <p class="mb-1" id="lookup-tracking"></p>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-lg me-1"></i> Add Service
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-currency-dollar me-1"></i> Record Payment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.customers.registration.create') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-pencil me-1"></i> Manual Registration
                </a>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="bi bi-people me-1"></i> View All Customers
                </a>
            </div>
        </div>

        <div class="admin-card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Tips</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0 small">
                    <li class="mb-2">For Passport: Place the bio page face down on the scanner</li>
                    <li class="mb-2">For Iqama: Ensure all text is clearly visible and not blurred</li>
                    <li>Good lighting improves OCR accuracy significantly</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function lookupCode() {
    const code = document.getElementById('manual-code').value;
    if (!code) return;

    // Placeholder for actual API call
    const resultDiv = document.getElementById('lookup-result');
    document.getElementById('lookup-name').textContent = 'Customer: John Doe';
    document.getElementById('lookup-tracking').textContent = 'Tracking: BMT-2024-XXXXX';
    resultDiv.classList.remove('d-none');
}
</script>
@endpush
