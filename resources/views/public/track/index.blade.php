<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Service - Bin Mishal Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .track-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 700px;
            margin: 0 auto;
            overflow: hidden;
        }
        .track-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .track-body {
            padding: 40px;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 8px 16px;
        }
        .service-item {
            border-left: 4px solid #667eea;
            padding-left: 15px;
            margin-bottom: 20px;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
            border: 2px solid white;
        }
    </style>
</head>
<body>
    <div class="track-card">
        <div class="track-header">
            <i class="bi bi-geo-alt" style="font-size: 3rem;"></i>
            <h2 class="mt-3 mb-0">Track Your Service</h2>
            <p class="mb-0">Bin Mishal Travels</p>
        </div>
        
        <div class="track-body">
            @if($error)
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $error }}
                </div>
            @endif

            @if($result)
                <div class="text-center mb-4">
                    <p class="text-muted mb-1">Tracking Number</p>
                    <h4 class="mb-0">{{ $result['tracking_no'] }}</h4>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Customer</p>
                                <p class="mb-0"><strong>{{ $result['name_masked'] }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Registered Date</p>
                                <p class="mb-0"><strong>{{ $result['registered_date'] }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Status</p>
                                <span class="badge bg-{{ $result['status'] === 'completed' ? 'success' : ($result['status'] === 'pending' ? 'warning' : 'info') }} status-badge">
                                    {{ ucfirst($result['status']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3"><i class="bi bi-list-check me-2"></i>Services</h5>
                
                @foreach($result['services'] as $service)
                <div class="service-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $service['name'] }}</h6>
                            <span class="badge bg-secondary">SAR {{ number_format($service['amount'], 2) }}</span>
                        </div>
                        <span class="badge bg-{{ $service['status'] === 'Completed' ? 'success' : 'warning' }}">
                            {{ $service['status'] }}
                        </span>
                    </div>
                    
                    <div class="timeline mt-3">
                        <div class="timeline-item">
                            <small class="text-muted">Service Registered</small>
                            <p class="mb-0 small">Your request has been received</p>
                        </div>
                        <div class="timeline-item">
                            <small class="text-muted">Under Review</small>
                            <p class="mb-0 small">Our team is processing your request</p>
                        </div>
                        @if($service['status'] === 'Completed')
                        <div class="timeline-item">
                            <small class="text-success">Completed</small>
                            <p class="mb-0 small">Service has been completed</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Need help?</strong> Contact us via WhatsApp or visit our office for more information.
                </div>
            @else
                <p class="text-center text-muted mb-4">
                    Enter your tracking number to see the status of your service(s).
                </p>
                
                <form method="GET" action="{{ route('public.track') }}">
                    <div class="mb-3">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" name="tracking_no" class="form-control form-control-lg" 
                            placeholder="e.g., BMT-2024-XXXXX" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-search me-2"></i> Track
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
