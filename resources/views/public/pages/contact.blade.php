@extends('public.layouts.master')

@section('title', __('app.contact') . ' - ' . __('app.app_name'))

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="mb-3">{{ __('app.contact') }}</h1>
        <p class="lead opacity-75">Get in touch with us - We're here to help</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card card-custom p-4">
                    <h3 class="mb-4">Send us a Message</h3>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" placeholder="Your full name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="your@email.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" placeholder="+966 XX XXX XXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <select class="form-select">
                                    <option>General Inquiry</option>
                                    <option>Umrah Package</option>
                                    <option>Visa Service</option>
                                    <option>Flight Booking</option>
                                    <option>Complaint</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="5" placeholder="How can we help you?"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-custom btn-lg">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="card card-custom p-4 mb-4">
                    <h4 class="mb-4">{{ __('home.contact_info') }}</h4>
                    <div class="mb-4">
                        <h6><i class="fas fa-map-marker-alt text-primary-custom me-2"></i> Office Address</h6>
                        <p class="text-muted ms-4">Riyadh, Kingdom of Saudi Arabia<br>Near Al-Masyaf District</p>
                    </div>
                    <div class="mb-4">
                        <h6><i class="fas fa-phone text-primary-custom me-2"></i> Phone</h6>
                        <p class="text-muted ms-4">+966 XX XXX XXXX<br>+966 XX XXX XXXX</p>
                    </div>
                    <div class="mb-4">
                        <h6><i class="fab fa-whatsapp text-success me-2"></i> WhatsApp</h6>
                        <p class="text-muted ms-4">+966 XX XXX XXXX</p>
                    </div>
                    <div class="mb-4">
                        <h6><i class="fas fa-envelope text-primary-custom me-2"></i> Email</h6>
                        <p class="text-muted ms-4">info@binmishal.com<br>support@binmishal.com</p>
                    </div>
                    <div>
                        <h6><i class="fas fa-clock text-primary-custom me-2"></i> Working Hours</h6>
                        <p class="text-muted ms-4">Saturday - Thursday: 9:00 AM - 6:00 PM<br>Friday: Closed</p>
                    </div>
                </div>
                
                <!-- Branch Offices -->
                <div class="card card-custom p-4">
                    <h4 class="mb-4">Our Branches</h4>
                    <div class="mb-3">
                        <h6 class="text-primary-custom">Riyadh (Head Office)</h6>
                        <p class="text-muted small mb-0">+966 XX XXX XXXX</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary-custom">Jeddah</h6>
                        <p class="text-muted small mb-0">+966 XX XXX XXXX</p>
                    </div>
                    <div>
                        <h6 class="text-primary-custom">Dammam</h6>
                        <p class="text-muted small mb-0">+966 XX XXX XXXX</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
