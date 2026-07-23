<form action="{{ route('cargo', ['locale' => app()->getLocale()]) }}" method="GET" onsubmit="calculateCargoPrice(); return false;">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label small">{{ __('cargo.origin') }}</label>
            <select id="cargoOrigin" class="form-select" required>
                <option value="">{{ __('common.select') }}</option>
                <option value="JED">Jeddah</option>
                <option value="RUH">Riyadh</option>
                <option value="DMM">Dammam</option>
                <option value="MED">Medina</option>
                <option value="AHB">Abha</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('cargo.destination') }}</label>
            <select id="cargoDestination" class="form-select" required>
                <option value="">{{ __('common.select') }}</option>
                <option value="DAC">Dhaka</option>
                <option value="CGP">Chittagong</option>
                <option value="ZYL">Sylhet</option>
                <option value="RJY">Rajshahi</option>
                <option value="KHL">Khulna</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('cargo.type') }}</label>
            <select id="cargoType" class="form-select">
                <option value="air">{{ __('cargo.air_cargo') }}</option>
                <option value="sea">{{ __('cargo.sea_cargo') }}</option>
                <option value="land">{{ __('cargo.door_to_door') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small">{{ __('cargo.weight') }} (kg)</label>
            <input type="number" id="cargoWeight" class="form-control" min="1" max="1000" placeholder="e.g., 23">
        </div>
        <div class="col-12">
            <div id="cargoPriceResult" class="alert alert-info d-none mb-2">
                <strong>{{ __('cargo.estimated_price') }}:</strong>
                <span id="cargoPrice" class="ms-2">SAR 0.00</span>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-calculator me-2"></i>
                {{ __('cargo.calculate_price') }}
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
function calculateCargoPrice() {
    const origin = document.getElementById('cargoOrigin').value;
    const destination = document.getElementById('cargoDestination').value;
    const type = document.getElementById('cargoType').value;
    const weight = parseFloat(document.getElementById('cargoWeight').value) || 0;
    
    if (!origin || !destination) {
        alert('Please select origin and destination');
        return;
    }
    
    // Base rates per kg (SAR)
    let baseRate = 15;
    if (type === 'air') baseRate = 25;
    if (type === 'sea') baseRate = 8;
    
    const shippingCost = weight * baseRate;
    const vatRate = 0.15;
    const vat = shippingCost * vatRate;
    const total = shippingCost + vat;
    
    document.getElementById('cargoPrice').textContent = 'SAR ' + total.toFixed(2);
    document.getElementById('cargoPriceResult').classList.remove('d-none');
}

// Update price on weight change
document.getElementById('cargoWeight')?.addEventListener('input', function() {
    if (this.value && document.getElementById('cargoOrigin').value && document.getElementById('cargoDestination').value) {
        calculateCargoPrice();
    }
});
</script>
@endpush
