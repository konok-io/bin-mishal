<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CMS\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a CMS page
     */
    public function show(string $slug = null): View
    {
        // If no slug, try to get homepage
        if (!$slug || $slug === '/') {
            $page = Page::where('is_homepage', true)
                ->where('status', 'published')
                ->first();
            
            if (!$page) {
                // Fallback to welcome page if no homepage set
                return view('welcome');
            }
        } else {
            $page = Page::whereJsonContains('slug', $slug)
                ->orWhere('slug', 'like', '%"' . $slug . '"%')
                ->first();
            
            // Try exact match on en slug
            if (!$page) {
                $page = Page::where('slug->en', $slug)
                    ->orWhere('slug', $slug)
                    ->first();
            }
        }
        
        if (!$page || $page->status !== 'published') {
            abort(404);
        }
        
        // Get page sections
        $sections = $page->sections()->with('items')->ordered()->get();
        
        // Get SEO data
        $seo = [
            'title' => $page->meta_title ?? $page->title,
            'description' => $page->meta_description ?? '',
            'keywords' => $page->meta_keywords ?? '',
            'image' => $page->og_image ? Storage::url($page->og_image) : null,
        ];
        
        // Check if page has custom template
        $template = $page->template ?? 'default';
        $templateView = "pages.{$template}";
        
        if (!view()->exists($templateView)) {
            $templateView = 'pages.default';
        }
        
        $locale = app()->getLocale();
        $showHeader = $page->show_header;
        $showFooter = $page->show_footer;
        $showBreadcrumb = $page->show_breadcrumb;
        
        return view($templateView, compact(
            'page',
            'sections',
            'seo',
            'locale',
            'showHeader',
            'showFooter',
            'showBreadcrumb'
        ));
    }
    
    /**
     * Preview a page (for admins)
     */
    public function preview(string $slug): View
    {
        $page = Page::whereJsonContains('slug', $slug)
            ->orWhere('slug', 'like', '%"' . $slug . '"%')
            ->first();
            
        if (!$page) {
            $page = Page::where('slug->en', $slug)
                ->orWhere('slug', $slug)
                ->first();
        }
        
        if (!$page) {
            abort(404);
        }
        
        $sections = $page->sections()->with('items')->ordered()->get();
        
        $seo = [
            'title' => $page->meta_title ?? $page->title,
            'description' => $page->meta_description ?? '',
            'keywords' => $page->meta_keywords ?? '',
            'image' => $page->og_image ? Storage::url($page->og_image) : null,
        ];
        
        $locale = app()->getLocale();
        $showHeader = $page->show_header;
        $showFooter = $page->show_footer;
        $showBreadcrumb = $page->show_breadcrumb;
        
        $template = $page->template ?? 'default';
        $templateView = "pages.{$template}";
        
        if (!view()->exists($templateView)) {
            $templateView = 'pages.default';
        }
        
        return view($templateView, compact(
            'page',
            'sections',
            'seo',
            'locale',
            'showHeader',
            'showFooter',
            'showBreadcrumb'
        ));
    }
}
