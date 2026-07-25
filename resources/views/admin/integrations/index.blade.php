@extends('layouts.admin')
@section('title', 'Integrations & API Keys')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">Integrations & API Keys</h1>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Note:</strong> API keys should be configured in your <code>.env</code> file, not in the database.
    Click the documentation links below to learn how to obtain each API key.
</div>

@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif

<div class="row">
    {{-- Payment Gateway --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-credit-card me-2"></i>Payment Gateway
                </h5>
                <span class="badge bg-{{ $integrations['payment']['status'] === 'configured' ? 'success' : 'warning' }}">
                    {{ $integrations['payment']['status'] === 'configured' ? 'Connected' : 'Not Connected' }}
                </span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['payment']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Provider</th>
                        <td>Moyasar (Saudi-compatible)</td>
                    </tr>
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>MOYASAR_SECRET_KEY</code></td>
                    </tr>
                    <tr>
                        <th>Supported Methods</th>
                        <td>Mada, Visa, Mastercard, Apple Pay, STC Pay</td>
                    </tr>
                </table>
                <a href="{{ $integrations['payment']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    {{-- AI Chat Assistant --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-robot me-2"></i>AI Chat Assistant
                </h5>
                <span class="badge bg-{{ $integrations['ai_chat']['status'] === 'configured' ? 'success' : 'warning' }}">
                    {{ $integrations['ai_chat']['status'] === 'configured' ? 'Connected' : 'Not Connected' }}
                </span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['ai_chat']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>OPENAI_API_KEY</code> or <code>ANTHROPIC_API_KEY</code></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $integrations['ai_chat']['configured'] ? 'Ready to use' : 'Requires API key' }}</td>
                    </tr>
                </table>
                <a href="{{ $integrations['ai_chat']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    {{-- WhatsApp Business API --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-whatsapp me-2"></i>WhatsApp Business API
                </h5>
                <span class="badge bg-{{ $integrations['whatsapp']['status'] === 'configured' ? 'success' : 'warning' }}">
                    {{ $integrations['whatsapp']['status'] === 'configured' ? 'Connected' : 'Not Connected' }}
                </span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['whatsapp']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>WHATSAPP_API_TOKEN</code></td>
                    </tr>
                    <tr>
                        <th>Features</th>
                        <td>Broadcast messages, automated replies</td>
                    </tr>
                </table>
                <a href="{{ $integrations['whatsapp']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    {{-- Bulk Email Service --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-envelope me-2"></i>Bulk Email Service
                </h5>
                <span class="badge bg-{{ $integrations['email']['status'] === 'configured' ? 'success' : 'warning' }}">
                    {{ $integrations['email']['status'] === 'configured' ? 'Connected' : 'Not Connected' }}
                </span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['email']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>MAIL_MAILER</code>, <code>MAILGUN_*</code>, <code>SES_*</code></td>
                    </tr>
                    <tr>
                        <th>Features</th>
                        <td>Newsletter campaigns, transactional emails</td>
                    </tr>
                </table>
                <a href="{{ $integrations['email']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    {{-- Google Analytics --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Google Analytics 4
                </h5>
                <span class="badge bg-{{ $integrations['analytics']['status'] === 'configured' ? 'success' : 'warning' }}">
                    {{ $integrations['analytics']['status'] === 'configured' ? 'Connected' : 'Not Connected' }}
                </span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['analytics']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>GA_MEASUREMENT_ID</code></td>
                    </tr>
                    <tr>
                        <th>Features</th>
                        <td>Traffic tracking, conversion events, UTM campaigns</td>
                    </tr>
                </table>
                <a href="{{ $integrations['analytics']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    {{-- External Accounting --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calculator me-2"></i>External Accounting
                </h5>
                <span class="badge bg-secondary">Optional</span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['accounting']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>ACCOUNTING_API_KEY</code></td>
                    </tr>
                    <tr>
                        <th>Supported</th>
                        <td>QuickBooks, Zoho Books, Tally</td>
                    </tr>
                </table>
                <a href="{{ $integrations['accounting']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    {{-- Biometric Device --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-fingerprint me-2"></i>Biometric Device
                </h5>
                <span class="badge bg-secondary">Optional</span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['biometric']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>BIOMETRIC_API_ENDPOINT</code></td>
                    </tr>
                    <tr>
                        <th>Supported</th>
                        <td>ZKTeco, Hikvision, eSSL</td>
                    </tr>
                </table>
                <a href="{{ $integrations['biometric']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    {{-- ZATCA E-Invoicing --}}
    <div class="col-md-6 mb-4">
        <div class="admin-card h-100 border-warning">
            <div class="card-header d-flex justify-content-between align-items-center bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>ZATCA E-Invoicing
                </h5>
                <span class="badge bg-dark">Recommended</span>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $integrations['zatca']['description'] }}</p>
                <table class="table table-sm">
                    <tr>
                        <th>Environment Variable</th>
                        <td><code>ZATCA_API_KEY</code></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>Mandatory for Saudi businesses - Not yet implemented</td>
                    </tr>
                </table>
                <a href="{{ $integrations['zatca']['documentation_url'] }}" target="_blank" class="btn btn-sm btn-warning">
                    <i class="bi bi-book"></i> ZATCA Portal
                </a>
            </div>
        </div>
    </div>
</div>

<div class="admin-card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-terminal me-2"></i>Quick Reference - .env Configuration</h5>
    </div>
    <div class="card-body">
        <pre class="bg-dark text-light p-3 rounded"><code># Payment Gateway (Moyasar)
MOYASAR_SECRET_KEY=your_secret_key_here
MOYASAR_PUBLISHABLE_KEY=your_publishable_key_here

# AI Chat Assistant
OPENAI_API_KEY=sk-your-openai-key
# OR
ANTHROPIC_API_KEY=sk-ant-your-anthropic-key

# WhatsApp Business API
WHATSAPP_API_TOKEN=your_whatsapp_token
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id

# Email Service
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret

# Analytics
GA_MEASUREMENT_ID=G-XXXXXXXXXX

# ZATCA E-Invoicing (Recommended)
ZATCA_API_KEY=your_zatca_key</code></pre>
    </div>
</div>
@endsection
