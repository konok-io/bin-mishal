<form action="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}" method="GET">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label small">{{ __('umrah.departure_date') }}</label>
            <input type="date" name="departure_date" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('umrah.duration') }}</label>
            <select name="duration" class="form-select">
                <option value="7">7 {{ __('common.days') }}</option>
                <option value="14" selected>14 {{ __('common.days') }}</option>
                <option value="21">21 {{ __('common.days') }}</option>
                <option value="30">30 {{ __('common.days') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('umrah.travelers') }}</label>
            <select name="travelers" class="form-select">
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }} {{ __('common.person', ['count' => $i]) }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('umrah.room_type') }}</label>
            <select name="room_type" class="form-select">
                <option value="quad">{{ __('umrah.quad') }}</option>
                <option value="triple">{{ __('umrah.triple') }}</option>
                <option value="double">{{ __('umrah.double') }}</option>
                <option value="single">{{ __('umrah.single') }}</option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-kaaba me-2"></i>
                {{ __('common.search_packages') }}
            </button>
        </div>
    </div>
</form>
