@extends('public.layouts.master')

@section('title', __('app.contact') . ' - ' . __('app.app_name'))

@php
$officeLocations = \App\Models\OfficeLocation::active()->orderBy('sort_order')->get();
$primaryLocation = $officeLocations->where('is_headquarters', true)->first() ?? $officeLocations->first();
@endphp

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
                    
                    @forelse($officeLocations->take(1) as $location)
                        <div class="mb-4">
                            <h6><i class="fas fa-map-marker-alt text-primary-custom me-2"></i> {{ $location->name }}</h6>
                            <p class="text-muted ms-4">{{ $location->formatted_address }}</p>
                        </div>
                        @if($location->phone)
                            <div class="mb-4">
                                <h6><i class="fas fa-phone text-primary-custom me-2"></i> Phone</h6>
                                <p class="text-muted ms-4">{{ $location->phone }}</p>
                            </div>
                        @endif
                        @if($location->whatsapp)
                            <div class="mb-4">
                                <h6><i class="fab fa-whatsapp text-success me-2"></i> WhatsApp</h6>
                                <p class="text-muted ms-4">{{ $location->whatsapp }}</p>
                            </div>
                        @endif
                        @if($location->email)
                            <div class="mb-4">
                                <h6><i class="fas fa-envelope text-primary-custom me-2"></i> Email</h6>
                                <p class="text-muted ms-4">{{ $location->email }}</p>
                            </div>
                        @endif
                        @if($location->working_hours)
                            <div>
                                <h6><i class="fas fa-clock text-primary-custom me-2"></i> Working Hours</h6>
                                <p class="text-muted ms-4">{{ $location->working_hours }}</p>
                            </div>
                        @endif
                    @empty
                        <div class="mb-4">
                            <h6><i class="fas fa-map-marker-alt text-primary-custom me-2"></i> Office Address</h6>
                            <p class="text-muted ms-4">{{ settings('contact_address', 'Riyadh, Kingdom of Saudi Arabia') }}</p>
                        </div>
                        <div class="mb-4">
                            <h6><i class="fas fa-phone text-primary-custom me-2"></i> Phone</h6>
                            <p class="text-muted ms-4">{{ settings('contact_phone', '+966 XX XXX XXXX') }}</p>
                        </div>
                        <div class="mb-4">
                            <h6><i class="fas fa-envelope text-primary-custom me-2"></i> Email</h6>
                            <p class="text-muted ms-4">{{ settings('contact_email', 'info@binmishal.com') }}</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Branch Offices -->
                @if($officeLocations->count() > 1)
                    <div class="card card-custom p-4">
                        <h4 class="mb-4">Our Branches</h4>
                        @foreach($officeLocations as $location)
                            <div class="mb-3">
                                <h6 class="text-primary-custom">
                                    {{ $location->name }}
                                    @if($location->is_headquarters)
                                        <span class="badge bg-primary ms-2">HQ</span>
                                    @endif
                                </h6>
                                <p class="text-muted small mb-1">{{ $location->city }}</p>
                                @if($location->phone)
                                    <p class="text-muted small mb-0">{{ $location->phone }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
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
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
@if($primaryLocation)
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Find Us on Map</h3>
        <div class="row">
            <div class="col-lg-8">
                <x-google-map 
                    height="400px"
                    :lat="$primaryLocation->latitude ?? 24.7136"
                    :lng="$primaryLocation->longitude ?? 46.6753"
                    :zoom="$primaryLocation->map_zoom ?? 14"
                    address="{{ $primaryLocation->formatted_address }}"
                    :marker-title="$primaryLocation->name"
                />
            </div>
            <div class="col-lg-4">
                <div class="card card-custom p-4">
                    <h4>{{ $primaryLocation->name }}</h4>
                    <p><i class="fas fa-map-marker-alt text-success me-2"></i> {{ $primaryLocation->formatted_address }}</p>
                    @if($primaryLocation->phone)
                        <p><i class="fas fa-phone text-success me-2"></i> {{ $primaryLocation->phone }}</p>
                    @endif
                    @if($primaryLocation->email)
                        <p><i class="fas fa-envelope text-success me-2"></i> {{ $primaryLocation->email }}</p>
                    @endif
                    @if($primaryLocation->working_hours)
                        <p><i class="fas fa-clock text-success me-2"></i> {{ $primaryLocation->working_hours }}</p>
                    @endif
                    <a href="{{ $primaryLocation->google_maps_url }}" target="_blank" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-directions me-2"></i> Get Directions
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
