<form action="{{ route('services.visa', ['locale' => app()->getLocale()]) }}" method="GET">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label small">{{ __('visa.type') }}</label>
            <select name="visa_type" class="form-select">
                <option value="">{{ __('common.select') }} {{ __('visa.type') }}</option>
                <option value="tourist">Tourist Visa</option>
                <option value="business">Business Visa</option>
                <option value="work">Work Visa</option>
                <option value="family">Family Visit Visa</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('visa.entry_type') }}</label>
            <select name="entry_type" class="form-select">
                <option value="single">Single Entry</option>
                <option value="multiple">Multiple Entry</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('visa.duration') }}</label>
            <select name="duration" class="form-select">
                <option value="30">30 {{ __('common.days') }}</option>
                <option value="60">60 {{ __('common.days') }}</option>
                <option value="90">90 {{ __('common.days') }}</option>
                <option value="180">180 {{ __('common.days') }}</option>
                <option value="365">1 {{ __('common.year') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('visa.travelers') }}</label>
            <select name="travelers" class="form-select">
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-passport me-2"></i>
                {{ __('common.apply_now') }}
            </button>
        </div>
    </div>
</form>
