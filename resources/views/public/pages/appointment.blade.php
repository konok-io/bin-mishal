@extends('layouts.public')

@section('title', __('Book Appointment') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="appointment-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Book an Appointment')</h1>
        <p class="lead text-white">@lang('Schedule a consultation with our experts')</p>
    </div>
</section>

<!-- Appointment Form -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="appointment-card">
                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <input type="hidden" name="type" value="appointment">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('Full Name') *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">@lang('Email') *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">@lang('Phone') *</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">@lang('Appointment Type')</label>
                                <select name="appointment_type" class="form-select">
                                    <option value="visa">@lang('Visa Consultation')</option>
                                    <option value="investor">@lang('Investment Services')</option>
                                    <option value="umrah">@lang('Umrah Packages')</option>
                                    <option value="cargo">@lang('Cargo Services')</option>
                                    <option value="complaint">@lang('Complaint')</option>
                                    <option value="other">@lang('Other')</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">@lang('Preferred Date')</label>
                                <input type="date" name="appointment_date" class="form-control" min="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">@lang('Preferred Time')</label>
                                <select name="appointment_time" class="form-select">
                                    <option value="morning">@lang('Morning (9 AM - 12 PM)')</option>
                                    <option value="afternoon">@lang('Afternoon (12 PM - 4 PM)')</option>
                                    <option value="evening">@lang('Evening (4 PM - 6 PM)')</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">@lang('Message')</label>
                                <textarea name="message" class="form-control" rows="4" 
                                          placeholder="@lang('Please describe your inquiry...')"></textarea>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-calendar-check"></i> @lang('Request Appointment')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Contact Info -->
                <div class="row mt-4 g-4">
                    <div class="col-md-4 text-center">
                        <i class="bi bi-telephone text-success" style="font-size: 2rem;"></i>
                        <h5 class="mt-2">@lang('Call Us')</h5>
                        <p>{{ settings('phone', '+966 XX XXX XXXX') }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-whatsapp text-success" style="font-size: 2rem;"></i>
                        <h5 class="mt-2">@lang('WhatsApp')</h5>
                        <p>{{ settings('whatsapp', '+966 XX XXX XXXX') }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-envelope text-success" style="font-size: 2rem;"></i>
                        <h5 class="mt-2">@lang('Email')</h5>
                        <p>{{ settings('email', 'info@binmishal.com') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.appointment-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.appointment-card {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
</style>
@endpush
