@extends('public.layouts.master')

@section('title', 'Cargo Service - ' . __('app.app_name'))

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="mb-3">Cargo & Logistics Service</h1>
        <p class="lead opacity-75">Ship your goods safely from Saudi Arabia to Bangladesh</p>
    </div>
</section>

<!-- Cargo Service Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Cargo Booking Form -->
            <div class="col-lg-7">
                <div class="card card-custom p-4">
                    <h3 class="mb-4"><i class="fas fa-box me-2 text-primary-custom"></i>Book Cargo</h3>
                    <form id="cargoBookingForm">
                        <div class="row g-3">
                            <!-- Sender Info -->
                            <div class="col-12">
                                <h5 class="text-primary-custom mb-3">Sender Information</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="sender_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" name="sender_phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="sender_email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City *</label>
                                <select class="form-select" name="sender_city" required>
                                    <option value="">Select City</option>
                                    <option value="Riyadh">Riyadh</option>
                                    <option value="Jeddah">Jeddah</option>
                                    <option value="Dammam">Dammam</option>
                                    <option value="Mecca">Mecca</option>
                                    <option value="Medina">Medina</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="sender_address" rows="2" required></textarea>
                            </div>

                            <!-- Receiver Info -->
                            <div class="col-12 mt-4">
                                <h5 class="text-primary-custom mb-3">Receiver Information</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="receiver_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" name="receiver_phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="receiver_email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">District *</label>
                                <select class="form-select" name="receiver_city" required>
                                    <option value="">Select District</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Chittagong">Chittagong</option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                    <option value="Khulna">Khulna</option>
                                    <option value="Barishal">Barishal</option>
                                    <option value="Rangpur">Rangpur</option>
                                    <option value="Mymensingh">Mymensingh</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="receiver_address" rows="2" required></textarea>
                            </div>

                            <!-- Cargo Details -->
                            <div class="col-12 mt-4">
                                <h5 class="text-primary-custom mb-3">Cargo Details</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cargo Type *</label>
                                <select class="form-select" name="cargo_type_id" required>
                                    <option value="">Select Type</option>
                                    <option value="1">Documents</option>
                                    <option value="2">Electronics</option>
                                    <option value="3">Clothing</option>
                                    <option value="4">Food Items</option>
                                    <option value="5">Parcel</option>
                                    <option value="6">Commercial Goods</option>
                                    <option value="7">Household Goods</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Weight (kg) *</label>
                                <input type="number" class="form-control" name="weight" step="0.1" min="0.1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" value="1" min="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Declared Value (SAR)</label>
                                <input type="number" class="form-control" name="declared_value" step="0.01">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="cargo_description" rows="2" placeholder="Please describe your cargo contents"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Special Instructions</label>
                                <textarea class="form-control" name="special_instructions" rows="2" placeholder="Any special handling instructions"></textarea>
                            </div>

                            <!-- Price Calculation -->
                            <div class="col-12 mt-4">
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Estimated Total:</strong><br>
                                            <small>Including VAT (15%)</small>
                                        </div>
                                        <div>
                                            <span class="fs-3 fw-bold text-success" id="estimatedPrice">SAR 0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-custom btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Booking
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-5">
                <!-- Track Cargo -->
                <div class="card card-custom p-4 mb-4">
                    <h4 class="mb-4"><i class="fas fa-search me-2 text-primary-custom"></i>Track Your Cargo</h4>
                    <form action="{{ route('cargo.track', ['locale' => app()->getLocale()]) }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="trackingNumber" placeholder="Enter Tracking Number" required>
                            <button type="submit" class="btn btn-primary-custom">Track</button>
                        </div>
                    </form>
                    <p class="text-muted small mt-2 mb-0">Example: CG-2026-000001</p>
                </div>

                <!-- Service Info -->
                <div class="card card-custom p-4 mb-4">
                    <h4 class="mb-4"><i class="fas fa-info-circle me-2 text-primary-custom"></i>How It Works</h4>
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0 me-3">
                            <span class="badge bg-primary rounded-circle p-2">1</span>
                        </div>
                        <div>
                            <h6>Book Online</h6>
                            <p class="small text-muted mb-0">Fill out the booking form with cargo details</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0 me-3">
                            <span class="badge bg-primary rounded-circle p-2">2</span>
                        </div>
                        <div>
                            <h6>Pickup & Process</h6>
                            <p class="small text-muted mb-0">We collect your cargo from your location</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0 me-3">
                            <span class="badge bg-primary rounded-circle p-2">3</span>
                        </div>
                        <div>
                            <h6>Transit & Customs</h6>
                            <p class="small text-muted mb-0">Ship to Bangladesh with customs clearance</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <span class="badge bg-success rounded-circle p-2">4</span>
                        </div>
                        <div>
                            <h6>Delivery</h6>
                            <p class="small text-muted mb-0">Door-to-door delivery to receiver</p>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="card card-custom p-4">
                    <h4 class="mb-4"><i class="fas fa-star me-2 text-primary-custom"></i>Why Choose Us</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Door-to-door service</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Real-time tracking</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Competitive pricing</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Fast delivery (3-7 days)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Customs handling included</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>24/7 customer support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Our Rates</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-custom text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-file fa-3x text-primary-custom mb-3"></i>
                        <h5>Documents</h5>
                        <p class="text-muted mb-2">Up to 1 kg</p>
                        <h3 class="text-success">SAR 25</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-box fa-3x text-primary-custom mb-3"></i>
                        <h5>Small Parcel</h5>
                        <p class="text-muted mb-2">Up to 5 kg</p>
                        <h3 class="text-success">SAR 75</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-3x text-primary-custom mb-3"></i>
                        <h5>Medium Box</h5>
                        <p class="text-muted mb-2">Up to 15 kg</p>
                        <h3 class="text-success">SAR 150</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-cube fa-3x text-primary-custom mb-3"></i>
                        <h5>Large Box</h5>
                        <p class="text-muted mb-2">Up to 30 kg</p>
                        <h3 class="text-success">SAR 250</h3>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-center text-muted mt-4">
            * Prices are indicative. Final price depends on actual weight and dimensions.<br>
            * VAT (15%) is included in all prices.
        </p>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price calculation on weight change
    const weightInput = document.querySelector('input[name="weight"]');
    const priceDisplay = document.getElementById('estimatedPrice');
    
    weightInput.addEventListener('input', function() {
        const weight = parseFloat(this.value) || 0;
        const baseRate = 15; // SAR per kg
        const vatRate = 0.15;
        
        const shippingCost = weight * baseRate;
        const vat = shippingCost * vatRate;
        const total = shippingCost + vat;
        
        priceDisplay.textContent = 'SAR ' + total.toFixed(2);
    });
    
    // Form submission
    document.getElementById('cargoBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Booking form submitted! In production, this would submit to the server and create a cargo booking.');
        // In production, submit to server
    });
});
</script>
@endpush
