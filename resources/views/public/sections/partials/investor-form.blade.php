<form action="{{ route('investor', ['locale' => app()->getLocale()]) }}" method="GET">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label small">{{ __('investor.service_type') }}</label>
            <select name="service_type" class="form-select">
                <option value="">{{ __('common.select') }}</option>
                <option value="misa_license">MISA License</option>
                <option value="foreign_investment">Foreign Investment</option>
                <option value="company_registration">Company Registration</option>
                <option value="branch_registration">Branch Registration</option>
                <option value="consultation">Investor Consultation</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('investor.investment_range') }}</label>
            <select name="investment_range" class="form-select">
                <option value="">{{ __('common.select') }}</option>
                <option value="under_1m">Under SAR 1 Million</option>
                <option value="1m_10m">SAR 1 - 10 Million</option>
                <option value="10m_50m">SAR 10 - 50 Million</option>
                <option value="over_50m">Over SAR 50 Million</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('common.full_name') }}</label>
            <input type="text" name="name" class="form-control" placeholder="{{ __('common.enter_name') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('common.email') }}</label>
            <input type="email" name="email" class="form-control" placeholder="email@example.com">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-chart-line me-2"></i>
                {{ __('common.get_started') }}
            </button>
        </div>
    </div>
</form>
