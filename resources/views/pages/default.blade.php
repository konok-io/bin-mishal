{{-- Default Page Template --}}
<x-app-layout :seo="$seo" :showHeader="$showHeader" :showFooter="$showFooter">
    @if($showBreadcrumb && !$page->is_homepage)
        <x-public.breadcrumb :page="$page" :locale="$locale" />
    @endif
    
    {{-- Hero Section --}}
    @if($page->hero_type !== 'none' && $page->hero_type)
        <x-public.page-hero :page="$page" />
    @endif
    
    {{-- Page Content Sections --}}
    <div class="page-content">
        @forelse($sections as $section)
            <x-page-section-component :section="$section" />
        @empty
            <div class="container py-16 text-center">
                <p class="text-gray-500">{{ __('cms.page_under_construction') }}</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
