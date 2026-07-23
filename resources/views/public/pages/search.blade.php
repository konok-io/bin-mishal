@extends('layouts.public')

@section('title', __('Search Results') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="search-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Search Results')</h1>
        <p class="lead text-white">@lang('Results for:') "{{ $query }}"</p>
    </div>
</section>

<!-- Search Form -->
<section class="py-4 bg-light">
    <div class="container">
        <form method="GET" action="{{ route('search') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="q" class="form-control form-control-lg" 
                       placeholder="@lang('Search for services, blog posts, jobs...')" 
                       value="{{ $query }}" required>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-lg">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>@lang('All')</option>
                    <option value="services" {{ $type === 'services' ? 'selected' : '' }}>@lang('Services')</option>
                    <option value="blog" {{ $type === 'blog' ? 'selected' : '' }}>@lang('Blog/News')</option>
                    <option value="jobs" {{ $type === 'jobs' ? 'selected' : '' }}>@lang('Jobs')</option>
                    <option value="faq" {{ $type === 'faq' ? 'selected' : '' }}>@lang('FAQs')</option>
                    <option value="downloads" {{ $type === 'downloads' ? 'selected' : '' }}>@lang('Downloads')</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Results -->
<section class="py-5">
    <div class="container">
        <div id="search-results">
            <div class="text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">@lang('Loading...')</span>
                </div>
                <p class="mt-3">@lang('Searching...')</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const query = '{{ $query }}';
    const type = '{{ $type }}';
    const resultsContainer = document.getElementById('search-results');
    
    if (query && query.length >= 2) {
        fetch(`/api/search?q=${encodeURIComponent(query)}&type=${type}`)
            .then(response => response.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    let html = '<div class="row g-4">';
                    
                    data.results.forEach(item => {
                        const typeLabels = {
                            'service': '@lang("Service")',
                            'blog': '@lang("Blog Post")',
                            'news': '@lang("News")',
                            'job': '@lang("Job")',
                            'faq': '@lang("FAQ")',
                            'download': '@lang("Download")',
                            'visa': '@lang("Visa Service")',
                        };
                        
                        html += `
                            <div class="col-md-6">
                                <div class="result-card">
                                    <span class="badge bg-success mb-2">${typeLabels[item.type] || item.type}</span>
                                    <h4><a href="${item.url}">${item.title}</a></h4>
                                    ${item.subtitle ? '<p class="text-muted mb-0">' + item.subtitle + '</p>' : ''}
                                    ${item.price ? '<p class="text-primary fw-bold mb-0">SAR ' + item.price + '</p>' : ''}
                                </div>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    html += `<p class="text-muted mt-4">@lang('Found') ${data.total} @lang('results')</p>`;
                    
                    resultsContainer.innerHTML = html;
                } else {
                    resultsContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
                            <h3 class="mt-3">@lang('No results found')</h3>
                            <p>@lang('Try different keywords or browse our services.')</p>
                            <a href="{{ route('services') }}" class="btn btn-success mt-3">@lang('Browse Services')</a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultsContainer.innerHTML = `
                    <div class="alert alert-danger">
                        @lang('An error occurred while searching. Please try again.')
                    </div>
                `;
            });
    } else {
        resultsContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
                <h3 class="mt-3">@lang('Enter a search term')</h3>
                <p>@lang('Please enter at least 2 characters to search.')</p>
            </div>
        `;
    }
});
</script>
@endpush

@push('styles')
<style>
.search-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.result-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    height: 100%;
    transition: transform 0.3s;
}
.result-card:hover {
    transform: translateY(-5px);
}
.result-card h4 {
    margin: 10px 0;
}
.result-card h4 a {
    color: #333;
    text-decoration: none;
}
.result-card h4 a:hover {
    color: var(--primary);
}
</style>
@endpush
