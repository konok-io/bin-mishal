<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class GoogleMap extends Component
{
    public string $height;
    public ?string $zoom;
    public ?float $lat;
    public ?float $lng;
    public string $address;
    public ?string $markerTitle;
    public bool $draggable;
    public string $mapType;

    public function __construct(
        string $height = '400px',
        ?string $zoom = null,
        ?float $lat = null,
        ?float $lng = null,
        string $address = '',
        ?string $markerTitle = null,
        bool $draggable = false,
        string $mapType = 'roadmap'
    ) {
        $this->height = $height;
        $this->zoom = $zoom ?? setting('google_maps_zoom', '14');
        $this->lat = $lat ?? (float) setting('google_maps_lat', '24.7136');
        $this->lng = $lng ?? (float) setting('google_maps_lng', '46.6753');
        $this->address = $address;
        $this->markerTitle = $markerTitle ?? config('app.name');
        $this->draggable = $draggable;
        $this->mapType = $mapType;
    }

    public function getApiKey(): ?string
    {
        return setting('google_maps_api_key');
    }

    public function hasApiKey(): bool
    {
        return !empty($this->getApiKey());
    }

    public function getMapId(): string
    {
        return 'google-map-' . uniqid();
    }

    public function render()
    {
        return view('components.google-map');
    }
}
