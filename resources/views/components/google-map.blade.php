<div class="google-map-wrapper">
    @if($hasApiKey())
        <div id="{{ $getMapId() }}" 
             class="google-map-container"
             style="height: {{ $height }};"
             data-lat="{{ $lat }}"
             data-lng="{{ $lng }}"
             data-zoom="{{ $zoom }}"
             data-map-type="{{ $mapType }}"
             data-marker-title="{{ $markerTitle }}"
             data-address="{{ $address }}"
             data-draggable="{{ $draggable ? 'true' : 'false' }}">
        </div>
    @else
        <div class="google-map-placeholder" style="height: {{ $height }};">
            <div class="placeholder-content text-center">
                <i class="bi bi-geo-alt" style="font-size: 3rem; color: #ccc;"></i>
                <h5 class="mt-3 text-muted">@lang('Map not available')</h5>
                <p class="text-muted small">
                    @lang('Google Maps API key not configured.')
                    <br>
                    <a href="{{ locale_route('home') }}">@lang('Contact us for location details.')</a>
                </p>
            </div>
            @if($address)
                <div class="address-overlay text-center mt-3">
                    <i class="bi bi-building"></i>
                    <strong>{{ $address }}</strong>
                </div>
            @endif
        </div>
    @endif
</div>

@if($hasApiKey())
@push('scripts')
<script>
function initGoogleMap{{ str_replace('-', '_', $getMapId()) }}() {
    const mapId = '{{ $getMapId() }}';
    const mapElement = document.getElementById(mapId);
    
    if (!mapElement) return;
    
    const lat = parseFloat(mapElement.dataset.lat) || 24.7136;
    const lng = parseFloat(mapElement.dataset.lng) || 46.6753;
    const zoom = parseInt(mapElement.dataset.zoom) || 14;
    const mapType = mapElement.dataset.mapType || 'roadmap';
    const markerTitle = mapElement.dataset.markerTitle || 'Location';
    const address = mapElement.dataset.address || '';
    const draggable = mapElement.dataset.draggable === 'true';
    
    const mapOptions = {
        center: { lat: lat, lng: lng },
        zoom: zoom,
        mapTypeId: mapType,
        disableDefaultUI: false,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: true,
        rotateControl: true,
        fullscreenControl: true,
        styles: [] // Can add custom map styles here
    };
    
    const map = new google.maps.Map(mapElement, mapOptions);
    
    // Marker
    const markerOptions = {
        position: { lat: lat, lng: lng },
        map: map,
        title: markerTitle,
        draggable: draggable,
        animation: google.maps.Animation.DROP
    };
    
    const marker = new google.maps.Marker(markerOptions);
    
    // Info Window
    if (address || markerTitle) {
        const infoWindow = new google.maps.InfoWindow({
            content: '<div style="padding: 10px;">' +
                     '<strong>' + markerTitle + '</strong>' +
                     (address ? '<br><small>' + address + '</small>' : '') +
                     '</div>'
        });
        
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });
    }
    
    // Dragend event for draggable markers
    if (draggable) {
        marker.addListener('dragend', function() {
            const position = marker.getPosition();
            console.log('New position:', position.lat(), position.lng());
        });
    }
}

// Load Google Maps API
function loadGoogleMapsAPI() {
    const apiKey = '{{ $getApiKey() }}';
    if (!apiKey) return;
    
    // Check if already loaded
    if (window.google && window.google.maps) return;
    
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=' + apiKey + '&callback=initGoogleMap{{ str_replace('-', '_', $getMapId()) }}';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadGoogleMapsAPI);
} else {
    loadGoogleMapsAPI();
}
</script>
@endpush
@endif

@push('styles')
<style>
.google-map-wrapper {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.google-map-container {
    width: 100%;
    border-radius: 12px;
}

.google-map-placeholder {
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 2px dashed #dee2e6;
}

.placeholder-content {
    padding: 40px 20px;
}

.address-overlay {
    padding: 15px 20px;
    background: white;
    border-radius: 8px;
    margin: 0 20px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.address-overlay i {
    margin-right: 8px;
    color: var(--primary);
}
</style>
@endpush
