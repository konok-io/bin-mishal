<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Document - Bin Mishal Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .verify-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .verify-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .verify-body {
            padding: 40px;
        }
        .result-icon {
            font-size: 80px;
        }
        .result-valid {
            color: #28a745;
        }
        .result-invalid {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="verify-header">
            <i class="bi bi-shield-check" style="font-size: 3rem;"></i>
            <h2 class="mt-3 mb-0">Document Verification</h2>
            <p class="mb-0">Bin Mishal Travels</p>
        </div>
        
        <div class="verify-body">
            @if($error)
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $error }}
                </div>
            @endif

            @if($result)
                <div class="text-center">
                    @if($result['is_valid'])
                        <div class="result-icon result-valid">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h3 class="text-success mt-3">✅ Verified</h3>
                        <p class="text-success">This is an authentic document issued by Bin Mishal Travels</p>
                    @else
                        <div class="result-icon result-invalid">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <h3 class="text-danger mt-3">❌ Invalid</h3>
                        <p class="text-danger">This document has been revoked or is not valid.</p>
                    @endif
                    
                    <hr>
                    
                    <table class="table table-borderless text-start">
                        <tr>
                            <td class="text-muted">Document Type:</td>
                            <td><strong>{{ $result['document_type'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Issue Date:</td>
                            <td><strong>{{ $result['issue_date'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Branch:</td>
                            <td><strong>{{ $result['branch'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Customer Name:</td>
                            <td><strong>{{ $result['name_masked'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">ID Number:</td>
                            <td><strong>{{ $result['id_masked'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <span class="badge bg-{{ $result['is_valid'] ? 'success' : 'danger' }}">
                                    {{ $result['status'] }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            @else
                <p class="text-center text-muted mb-4">
                    Enter the verification code or scan the QR code from your document to verify its authenticity.
                </p>
                
                <form method="GET" action="{{ route('public.verify') }}">
                    <div class="mb-3">
                        <label class="form-label">Verification Code / QR Code</label>
                        <input type="text" name="code" class="form-control form-control-lg" 
                            placeholder="Enter code or scan QR" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-search me-2"></i> Verify
                    </button>
                </form>
            @endif
            
            <div class="text-center mt-4">
                <a href="/" class="text-muted small">
                    <i class="bi bi-house me-1"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
