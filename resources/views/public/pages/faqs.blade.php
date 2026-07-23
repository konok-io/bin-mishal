@extends('public.layouts.master')

@section('title', __('app.faqs') . ' - ' . __('app.app_name'))

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>{{ __('app.faqs') }}</h1>
        <p class="lead">Find answers to frequently asked questions</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @php
                $faqs = \App\Models\Faq::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->groupBy('category');
                @endphp
                
                @forelse($faqs as $category => $items)
                    <div class="faq-category mb-5">
                        <h3 class="mb-4">{{ $category ?: 'General' }}</h3>
                        
                        <div class="accordion" id="faq-accordion-{{ $loop->index }}">
                            @foreach($items as $faq)
                                <div class="accordion-item mb-2 border rounded">
                                    <h2 class="accordion-header" id="faq-heading-{{ $faq->id }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#faq-{{ $faq->id }}"
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                         aria-labelledby="faq-heading-{{ $faq->id }}"
                                         data-bs-parent="#faq-accordion-{{ $loop->parent->index }}">
                                        <div class="accordion-body">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-question-circle text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 text-muted">No FAQs available</h3>
                        <p>Please check back later for frequently asked questions.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Contact CTA -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="card bg-primary text-white p-5">
                    <h3>Still have questions?</h3>
                    <p>Can't find what you're looking for? Contact our support team.</p>
                    <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="btn btn-light btn-lg">
                        <i class="bi bi-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.faq-category h3 {
    color: var(--primary);
    border-bottom: 2px solid var(--primary);
    padding-bottom: 10px;
}
.accordion-button:not(.collapsed) {
    background-color: var(--accent-light);
    color: var(--primary);
}
</style>
@endpush
