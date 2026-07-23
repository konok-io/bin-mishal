<div>
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <form wire:submit="submit">
                <!-- Trip Type -->
                <div class="btn-group w-100 mb-4" role="group">
                    <input type="radio" class="btn-check" name="tripType" id="oneway" value="oneway" wire:model="tripType">
                    <label class="btn btn-outline-primary" for="oneway">One Way</label>

                    <input type="radio" class="btn-check" name="tripType" id="roundtrip" value="roundtrip" wire:model="tripType">
                    <label class="btn btn-outline-primary" for="roundtrip">Round Trip</label>
                </div>

                <div class="row g-3">
                    <!-- From -->
                    <div class="col-md-6">
                        <label class="form-label">From</label>
                        <select class="form-select" wire:model="fromAirport">
                            <option value="">Select Origin</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->iata_code }})</option>
                            @endforeach
                        </select>
                        @error('fromAirport') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- To -->
                    <div class="col-md-6">
                        <label class="form-label">To</label>
                        <select class="form-select" wire:model="toAirport">
                            <option value="">Select Destination</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->iata_code }})</option>
                            @endforeach
                        </select>
                        @error('toAirport') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- Departure -->
                    <div class="col-md-6">
                        <label class="form-label">Departure Date</label>
                        <input type="date" class="form-control" wire:model="departureDate">
                        @error('departureDate') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- Return -->
                    @if($tripType === 'roundtrip')
                    <div class="col-md-6">
                        <label class="form-label">Return Date</label>
                        <input type="date" class="form-control" wire:model="returnDate">
                        @error('returnDate') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Passengers -->
                    <div class="col-md-4">
                        <label class="form-label">Adults</label>
                        <select class="form-select" wire:model="adults">
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Children</label>
                        <select class="form-select" wire:model="children">
                            @for($i = 0; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cabin Class</label>
                        <select class="form-select" wire:model="cabinClass">
                            <option value="economy">Economy</option>
                            <option value="premium">Premium Economy</option>
                            <option value="business">Business</option>
                            <option value="first">First Class</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4 py-2">
                    <i class="bi bi-search me-2"></i> Search Flights
                </button>
            </form>
        </div>
    </div>
</div>
