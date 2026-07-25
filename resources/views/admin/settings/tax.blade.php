@extends('layouts.admin')
@section('title', 'Tax & VAT Settings')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">Tax & VAT Settings</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>VAT Configuration</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.tax.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="tax_vat_enabled" name="tax_vat_enabled" 
                                {{ $settings['tax_vat_enabled'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="tax_vat_enabled">
                                <strong>Enable VAT</strong>
                            </label>
                        </div>
                        <small class="text-muted">When enabled, VAT will be added to applicable services</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">VAT Rate (%)</label>
                            <input type="number" class="form-control" name="tax_vat_rate" 
                                value="{{ $settings['tax_vat_rate'] }}" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">VAT Registration Number</label>
                            <input type="text" class="form-control" name="tax_vat_number" 
                                value="{{ $settings['tax_vat_number'] }}" placeholder="e.g., 123456789012345">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">VAT Applicable Services</label>
                        <input type="text" class="form-control" name="tax_vat_applicable_services" 
                            value="{{ $settings['tax_vat_applicable_services'] }}">
                        <small class="text-muted">Comma-separated service names that VAT applies to (e.g., flight,umrah,visa)</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>ZATCA E-Invoicing:</strong> For full Saudi VAT compliance, consider integrating with ZATCA's e-invoicing system (Fatoora).
                        Configure in <a href="{{ route('admin.integrations.index') }}">Integrations</a>.
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save VAT Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>About VAT</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Current Rate:</strong> {{ $settings['tax_vat_rate'] }}%</p>
                <p class="mb-2"><strong>Status:</strong> 
                    <span class="badge bg-{{ $settings['tax_vat_enabled'] ? 'success' : 'secondary' }}">
                        {{ $settings['tax_vat_enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>
                </p>
                <hr>
                <p class="small text-muted mb-0">
                    Saudi Arabia requires 15% VAT on most goods and services.
                    Make sure your VAT registration number is correct for tax invoices.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
