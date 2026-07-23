<?php

declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS\Page;
use App\Models\CMS\SeoRedirect;
use App\Services\CMS\CMSCache;
use App\Services\CMS\MenuBuilder;
use App\Services\CMS\SectionDataResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PageController extends Controller
{
    public function __construct(
        protected SectionDataResolver $dataResolver,
        protected MenuBuilder $menuBuilder,
        protected CMSCache $cache
    ) {}

    /**
     * Display a page by its slug.
     */
    public function show(Request $request, string $locale, ?string $slug = null): \Illuminate\Http\Response
    {
        // Check for SEO redirect first
        $path = $request->path();
        $redirect = SeoRedirect::shouldRedirect($path);

        if ($redirect) {
            $redirect->recordHit();

            return Response::redirectTo(
                $redirect->new_path ?? '/',
                $redirect->getCode()
            );
        }

        // If no slug, show homepage
        if (!$slug) {
            return $this->showHomepage($locale);
        }

        // Find page by slug
        $page = $this->findPage($slug, $locale);

        if (!$page) {
            abort(404);
        }

        // Check visibility
        if (!$page->isVisible() && !$request->has('preview')) {
            abort(404);
        }

        return $this->renderPage($page, $locale, $request->has('preview'));
    }

    /**
     * Show the homepage.
     */
    protected function showHomepage(string $locale): \Illuminate\Http\Response
    {
        // Try to find a page marked as homepage
        $page = Page::getHomepage();

        // If no CMS homepage, render the static welcome page
        if (!$page) {
            return $this->renderStaticHomepage($locale);
        }

        return $this->renderPage($page, $locale);
    }

    /**
     * Find a page by slug.
     */
    protected function findPage(string $slug, string $locale): ?Page
    {
        // Handle nested slugs (e.g., about/our-story)
        $parts = explode('/', $slug);
        $slug = array_pop($parts);

        return Page::where(function ($query) use ($slug, $locale) {
            $query->where("slug->{$locale}", $slug)
                  ->orWhere('slug->en', $slug);
        })
        ->visible()
        ->with(['parent', 'sections' => function ($q) {
            $q->where('status', true)->orderBy('order');
        }])
        ->first();
    }

    /**
     * Render a page.
     */
    protected function renderPage(Page $page, string $locale, bool $isPreview = false): \Illuminate\Http\Response
    {
        // Load sections with items
        $page->load(['sections' => function ($q) {
            $q->where('status', true)
              ->orderBy('order')
              ->with(['items' => function ($iq) {
                  $iq->where('status', true)->orderBy('order');
              }]);
        }]);

        // Filter visible sections
        $sections = $page->sections->filter(fn($s) => $s->isVisible());

        // Get menu data
        $headerMenu = $this->menuBuilder->header();
        $footerCol1 = $this->menuBuilder->footerCol1();
        $footerCol2 = $this->menuBuilder->footerCol2();
        $footerCol3 = $this->menuBuilder->footerCol3();
        $footerBottom = $this->menuBuilder->footerBottom();
        $mobileMenu = $this->menuBuilder->mobile();

        // Build SEO data
        $seo = [
            'title' => $page->getMetaTitle($locale),
            'description' => $page->getMetaDescription($locale),
            'keywords' => $page->meta_keywords[$locale] ?? $page->meta_keywords['en'] ?? null,
            'og_image' => $page->og_image,
            'canonical' => $page->canonical_url ?: $page->getUrl($locale),
            'noindex' => $page->noindex,
            'schema_type' => $page->schema_type,
        ];

        // Determine layout
        $layout = $page->layout ?? 'public';

        $viewData = [
            'page' => $page,
            'locale' => $locale,
            'sections' => $sections,
            'dataResolver' => $this->dataResolver,
            'headerMenu' => $headerMenu,
            'footerCol1' => $footerCol1,
            'footerCol2' => $footerCol2,
            'footerCol3' => $footerCol3,
            'footerBottom' => $footerBottom,
            'mobileMenu' => $mobileMenu,
            'seo' => $seo,
            'isPreview' => $isPreview,
            'showHeader' => $page->show_header,
            'showFooter' => $page->show_footer,
            'showBreadcrumb' => $page->show_breadcrumb,
        ];

        // Use template-specific view if it exists
        $view = "pages.{$page->template}";

        if (!view()->exists($view)) {
            $view = 'pages.default';
        }

        return response()->view($view, $viewData);
    }

    /**
     * Render the static homepage (fallback when no CMS homepage).
     */
    protected function renderStaticHomepage(string $locale): \Illuminate\Http\Response
    {
        $headerMenu = $this->menuBuilder->header();
        $footerCol1 = $this->menuBuilder->footerCol1();
        $footerCol2 = $this->menuBuilder->footerCol2();
        $footerCol3 = $this->menuBuilder->footerCol3();
        $mobileMenu = $this->menuBuilder->mobile();

        return response()->view('welcome', [
            'locale' => $locale,
            'headerMenu' => $headerMenu,
            'footerCol1' => $footerCol1,
            'footerCol2' => $footerCol2,
            'footerCol3' => $footerCol3,
            'mobileMenu' => $mobileMenu,
        ]);
    }

    /**
     * Preview a page (draft content).
     */
    public function preview(Request $request, string $locale, string $slug): \Illuminate\Http\Response
    {
        $page = $this->findPage($slug, $locale);

        if (!$page) {
            abort(404);
        }

        return $this->renderPage($page, $locale, true);
    }
}
