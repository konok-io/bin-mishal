<form action="{{ route('appointment', ['locale' => app()->getLocale()]) }}" method="GET">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label small">{{ __('appointment.service_type') }}</label>
            <select name="service_type" class="form-select">
                <option value="">{{ __('common.select') }}</option>
                <option value="visa_consultation">Visa Consultation</option>
                <option value="document_verification">Document Verification</option>
                <option value="booking_assistance">Booking Assistance</option>
                <option value="general_inquiry">General Inquiry</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('appointment.preferred_date') }}</label>
            <input type="date" name="preferred_date" class="form-control" min="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('appointment.preferred_time') }}</label>
            <select name="preferred_time" class="form-select">
                <option value="">{{ __('common.select') }}</option>
                <option value="morning">Morning (9AM - 12PM)</option>
                <option value="afternoon">Afternoon (12PM - 3PM)</option>
                <option value="evening">Evening (3PM - 6PM)</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('appointment.branch') }}</label>
            <select name="branch" class="form-select">
                <option value="">{{ __('common.select') }}</option>
                <option value="jeddah">Jeddah</option>
                <option value="riyadh">Riyadh</option>
                <option value="dammam">Dammam</option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-calendar-check me-2"></i>
                {{ __('common.book_appointment') }}
            </button>
        </div>
    </div>
</form>
