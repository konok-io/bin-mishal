<form action="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}" method="GET">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label small">{{ __('flight.from') }}</label>
            <select name="from" class="form-select">
                <option value="">{{ __('common.select') }} {{ __('flight.from') }}</option>
                <option value="JED">Jeddah (JED)</option>
                <option value="RUH">Riyadh (RUH)</option>
                <option value="DMM">Dammam (DMM)</option>
                <option value="MED">Medina (MED)</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('flight.to') }}</label>
            <select name="to" class="form-select">
                <option value="">{{ __('common.select') }} {{ __('flight.to') }}</option>
                <option value="DAC">Dhaka (DAC)</option>
                <option value="CGP">Chittagong (CGP)</option>
                <option value="ZYL">Sylhet (ZYL)</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('flight.departure_date') }}</label>
            <input type="date" name="departure_date" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('flight.return_date') }}</label>
            <input type="date" name="return_date" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label small">{{ __('flight.adults') }}</label>
            <select name="adults" class="form-select">
                @for($i = 1; $i <= 9; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small">{{ __('flight.children') }}</label>
            <select name="children" class="form-select">
                @for($i = 0; $i <= 4; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small">{{ __('flight.class') }}</label>
            <select name="class" class="form-select">
                <option value="economy">{{ __('flight.economy') }}</option>
                <option value="business">{{ __('flight.business') }}</option>
                <option value="first">{{ __('flight.first') }}</option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-2"></i>
                {{ __('common.search') }}
            </button>
        </div>
    </div>
</form>
