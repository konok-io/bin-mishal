@extends('layouts.public')

@section('title', __('Testimonials') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="testimonials-hero">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">@lang('What Our Clients Say')</h1>
            <p class="lead text-muted">@lang('Read reviews and testimonials from our valued customers')</p>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <!-- Filter Tabs -->
        <div class="testimonial-filters text-center mb-5">
            <button class="btn btn-success active" data-filter="all">@lang('All')</button>
            <button class="btn btn-outline-success" data-filter="flight">@lang('Flight')</button>
            <button class="btn btn-outline-success" data-filter="umrah">@lang('Umrah')</button>
            <button class="btn btn-outline-success" data-filter="visa">@lang('Visa')</button>
            <button class="btn btn-outline-success" data-filter="cargo">@lang('Cargo')</button>
            <button class="btn btn-outline-success" data-filter="investor">@lang('Investor')</button>
        </div>

        <!-- Testimonials Grid -->
        <div class="row" id="testimonials-grid">
            @forelse($testimonials as $testimonial)
            <div class="col-lg-4 col-md-6 mb-4 testimonial-card" data-service="{{ $testimonial->service_type ?? 'general' }}">
                <div class="testimonial-card-inner h-100">
                    <div class="testimonial-header">
                        <div class="testimonial-rating">
                            @for($i = 0; $i < $testimonial->rating; $i++)
                            <i class="bi bi-star-fill text-warning"></i>
                            @endfor
                            @for($i = $testimonial->rating; $i < 5; $i++)
                            <i class="bi bi-star text-warning"></i>
                            @endfor
                        </div>
                        @if($testimonial->service_type)
                        <span class="badge bg-success service-badge">{{ ucfirst($testimonial->service_type) }}</span>
                        @endif
                    </div>
                    
                    <div class="testimonial-quote">
                        <i class="bi bi-quote quote-icon"></i>
                        <p class="testimonial-text">{{ $testimonial->quote }}</p>
                    </div>

                    <div class="testimonial-author">
                        @if($testimonial->avatar)
                        <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }}" class="testimonial-avatar">
                        @else
                        <div class="testimonial-avatar-placeholder">
                            <i class="bi bi-person"></i>
                        </div>
                        @endif
                        <div class="testimonial-info">
                            <h5 class="testimonial-name">{{ $testimonial->name }}</h5>
                            @if($testimonial->designation)
                            <p class="testimonial-designation">{{ $testimonial->designation }}</p>
                            @endif
                            @if($testimonial->company)
                            <p class="testimonial-company">{{ $testimonial->company }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    @lang('No testimonials available at the moment.')
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Submit Review CTA -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3><i class="bi bi-chat-square-heart text-success"></i> @lang('Share Your Experience')</h3>
                <p class="text-muted mb-4">@lang("We value your feedback. Let us know about your experience with our services.")</p>
                <a href="{{ route('contact') }}?type=testimonial" class="btn btn-success btn-lg">
                    <i class="bi bi-pencil-square"></i> @lang('Submit Your Review')
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.testimonials-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    color: white;
    padding: 80px 0;
}
.testimonial-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}
.testimonial-filters .btn {
    border-radius: 25px;
    padding: 8px 20px;
}
.testimonial-filters .btn.active {
    background: var(--primary);
    color: white;
}
.testimonial-card {
    transition: transform 0.3s, opacity 0.3s;
}
.testimonial-card.filtered-out {
    opacity: 0.3;
    pointer-events: none;
}
.testimonial-card-inner {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.testimonial-card-inner:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}
.testimonial-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.testimonial-rating {
    font-size: 18px;
}
.service-badge {
    font-size: 12px;
}
.quote-icon {
    font-size: 32px;
    color: var(--accent);
    opacity: 0.3;
}
.testimonial-quote {
    position: relative;
    margin-bottom: 24px;
}
.testimonial-text {
    font-size: 1rem;
    line-height: 1.7;
    color: #555;
    font-style: italic;
}
.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.testimonial-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--accent);
}
.testimonial-avatar-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #999;
}
.testimonial-name {
    margin: 0;
    font-size: 1.1rem;
    color: #333;
}
.testimonial-designation {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}
.testimonial-company {
    margin: 0;
    font-size: 0.85rem;
    color: var(--primary);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.testimonial-filters .btn');
    const cards = document.querySelectorAll('.testimonial-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;

            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            cards.forEach(card => {
                const service = card.dataset.service;
                if (filter === 'all' || service === filter) {
                    card.classList.remove('filtered-out');
                } else {
                    card.classList.add('filtered-out');
                }
            });
        });
    });
});
</script>
@endpush
