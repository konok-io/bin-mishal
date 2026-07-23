<?php

namespace App\Livewire\Public;

use App\Models\Airport;
use App\Models\Airline;
use App\Models\VisaType;
use Livewire\Component;

class FlightSearchWidget extends Component
{
    public string $tripType = 'oneway';
    public ?int $fromAirport = null;
    public ?int $toAirport = null;
    public string $departureDate = '';
    public string $returnDate = '';
    public int $adults = 1;
    public int $children = 0;
    public int $infants = 0;
    public string $cabinClass = 'economy';
    public ?int $preferredAirline = null;

    protected $rules = [
        'tripType' => 'required|in:oneway,roundtrip,multicity',
        'fromAirport' => 'required|exists:airports,id',
        'toAirport' => 'required|exists:airports,id',
        'departureDate' => 'required|date|after_or_equal:today',
        'returnDate' => 'required_if:tripType,roundtrip|date|after_or_equal:departureDate',
        'adults' => 'required|integer|min:1|max:9',
        'children' => 'nullable|integer|min:0|max:8',
        'infants' => 'nullable|integer|min:0|max:4',
        'cabinClass' => 'required|in:economy,premium,business,first',
    ];

    public function submit()
    {
        $this->validate();

        return redirect()->route('flight-request.create', [
            'trip_type' => $this->tripType,
            'from_airport' => $this->fromAirport,
            'to_airport' => $this->toAirport,
            'departure_date' => $this->departureDate,
            'return_date' => $this->returnDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
            'cabin_class' => $this->cabinClass,
        ]);
    }

    public function render()
    {
        return view('livewire.public.flight-search-widget', [
            'airports' => Airport::where('status', 'active')->get(),
            'airlines' => Airline::where('status', 'active')->get(),
        ]);
    }
}
