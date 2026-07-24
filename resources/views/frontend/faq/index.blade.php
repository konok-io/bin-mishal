@extends('layouts.public')

@section('title', __('Frequently Asked Questions') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="faq-hero">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">@lang('Frequently Asked Questions')</h1>
            <p class="lead text-muted">@lang('Find answers to common questions about our services')</p>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Category Tabs -->
            <div class="col-lg-3">
                <div class="faq-categories sticky-top" style="top: 100px;">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-grid"></i> @lang('Categories')</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#all" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                                <i class="bi bi-list"></i> @lang('All Questions')
                            </a>
                            <a href="#general" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="bi bi-info-circle"></i> @lang('General')
                            </a>
                            <a href="#flight" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="bi bi-airplane"></i> @lang('Flight')
                            </a>
                            <a href="#umrah" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="bi bi-moon"></i> @lang('Umrah')
                            </a>
                            <a href="#visa" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="bi bi-passport"></i> @lang('Visa')
                            </a>
                            <a href="#cargo" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="bi bi-box"></i> @lang('Cargo')
                            </a>
                            <a href="#investor" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="bi bi-briefcase"></i> @lang('Investor')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Content -->
            <div class="col-lg-9">
                @foreach($categories as $category => $faqs)
                <div class="faq-category mb-5" id="{{ $category }}">
                    <h3 class="mb-4">
                        <i class="bi bi-folder-open text-success me-2"></i>
                        @lang(ucfirst($category))
                    </h3>
                    
                    <div class="accordion" id="faq-{{ $category }}">
                        @foreach($faqs as $faq)
                        <div class="accordion-item mb-3 border rounded">
                            <h2 class="accordion-header" id="faq-{{ $faq->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-answer-{{ $faq->id }}">
                                    <i class="bi bi-question-circle text-success me-2"></i>
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq-answer-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faq-{{ $category }}">
                                <div class="accordion-body">
                                    {!! nl2br($faq->answer) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @if($faqs->isEmpty())
                <div class="alert alert-info">
                    @lang('No FAQs available at the moment.')
                </div>
                @endif

                <!-- Contact CTA -->
                <div class="card bg-success text-white mt-5">
                    <div class="card-body text-center py-5">
                        <h3><i class="bi bi-chat-dots"></i> @lang("Can't find your answer?")</h3>
                        <p class="mb-4">@lang("We're here to help. Contact us and we'll get back to you shortly.")</p>
                        <a href="{{ locale_route('contact') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-envelope"></i> @lang('Contact Us')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.faq-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    color: white;
    padding: 80px 0;
}
.faq-categories .card {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.accordion-item {
    border: none !important;
    margin-bottom: 10px !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.accordion-button {
    font-weight: 600;
    color: #333;
}
.accordion-button:not(.collapsed) {
    background: #f8f9fa;
    color: var(--primary);
}
.accordion-button:focus {
    box-shadow: none;
    border-color: transparent;
}
.accordion-body {
    line-height: 1.8;
    color: #555;
}
</style>
@endpush
