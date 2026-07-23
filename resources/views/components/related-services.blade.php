@if(count($relatedItems) > 0)
<section class="related-services py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">@lang('Related Services')</h3>
            </div>
        </div>
        <div class="row g-4">
            @foreach($relatedItems as $item)
                @php
                    $info = $getServiceInfo($item);
                @endphp
                @if(!empty($info))
                    <div class="col-md-6 col-lg-3">
                        <div class="related-service-card card h-100">
                            @if(!empty($info['image']))
                                <img src="{{ $info['image'] }}" class="card-img-top" alt="{{ $info['title'] }}">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-primary text-white">
                                    <i class="bi bi-{{ $item['type'] === 'umrah' ? 'airplane' : ($item['type'] === 'visa' ? 'passport' : ($item['type'] === 'cargo' ? 'truck' : 'briefcase')) }} fs-1"></i>
                                </div>
                            @endif
                            <div class="card-body text-center">
                                <span class="badge bg-secondary mb-2">{{ $info['type'] }}</span>
                                <h5 class="card-title">{{ $info['title'] }}</h5>
                                <a href="{{ $info['route'] }}" class="btn btn-sm btn-outline-primary">
                                    @lang('Learn More')
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

@push('styles')
<style>
.related-service-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
    overflow: hidden;
}

.related-service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.related-service-card .card-img-top {
    height: 150px;
    object-fit: cover;
}

.related-service-card .card-img-top:where(.bg-primary) {
    height: 150px;
}

.related-service-card .card-title {
    font-size: 1rem;
    margin-bottom: 0.75rem;
    min-height: 2.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
