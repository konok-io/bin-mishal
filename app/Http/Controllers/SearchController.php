<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use App\Models\Content\Faq;
use App\Models\Content\Testimonial;
use App\Models\CMS\Download;
use App\Models\Job;
use App\Models\UmrahPackage;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    /**
     * Global search across all content types
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');
        $locale = app()->getLocale();
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'total' => 0,
                'query' => $query,
            ]);
        }
        
        $query = '%' . $query . '%';
        $results = [];
        
        // Search Services (Umrah Packages)
        if ($type === 'all' || $type === 'services') {
            $services = UmrahPackage::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', $query)
                      ->orWhere('description', 'LIKE', $query);
                })
                ->select('id', 'name', 'slug', 'price', 'image')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'service',
                        'title' => $item->name,
                        'url' => route('services.umrah.package', $item->slug ?? $item->id),
                        'image' => $item->image,
                        'price' => $item->price,
                    ];
                });
            
            $results = array_merge($results, $services->toArray());
        }
        
        // Search Blog/News Posts
        if ($type === 'all' || $type === 'blog') {
            $posts = Post::where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', $query)
                      ->orWhere('content', 'LIKE', $query);
                })
                ->select('id', 'title', 'slug', 'featured_image', 'type')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $route = $item->type === 'news' ? 'news.detail' : 'blog.detail';
                    return [
                        'type' => $item->type,
                        'title' => $item->title,
                        'url' => route($route, $item->slug ?? $item->id),
                        'image' => $item->featured_image,
                    ];
                });
            
            $results = array_merge($results, $posts->toArray());
        }
        
        // Search Jobs
        if ($type === 'all' || $type === 'jobs') {
            $jobs = Job::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', $query)
                      ->orWhere('description', 'LIKE', $query);
                })
                ->select('id', 'title', 'slug', 'department', 'location')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'job',
                        'title' => $item->title,
                        'subtitle' => $item->department . ' - ' . $item->location,
                        'url' => route('careers.show', $item->slug ?? $item->id),
                    ];
                });
            
            $results = array_merge($results, $jobs->toArray());
        }
        
        // Search Downloads
        if ($type === 'all' || $type === 'downloads') {
            $downloads = Download::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', $query)
                      ->orWhere('description', 'LIKE', $query);
                })
                ->select('id', 'title', 'file_url', 'category')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'download',
                        'title' => $item->title,
                        'subtitle' => $item->category,
                        'url' => $item->file_url,
                    ];
                });
            
            $results = array_merge($results, $downloads->toArray());
        }
        
        // Search FAQs
        if ($type === 'all' || $type === 'faq') {
            $faqs = Faq::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('question', 'LIKE', $query)
                      ->orWhere('answer', 'LIKE', $query);
                })
                ->select('id', 'question', 'answer')
                ->limit(3)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'faq',
                        'title' => Str::limit($item->question, 50),
                        'subtitle' => Str::limit($item->answer, 80),
                        'url' => route('faqs'),
                    ];
                });
            
            $results = array_merge($results, $faqs->toArray());
        }
        
        // Search Visa Types
        if ($type === 'all' || $type === 'visa') {
            $visas = VisaType::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', $query)
                      ->orWhere('description', 'LIKE', $query);
                })
                ->select('id', 'name', 'slug', 'fee')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'visa',
                        'title' => $item->name,
                        'subtitle' => 'SAR ' . number_format($item->fee ?? 0),
                        'url' => route('services.visa.service', $item->slug ?? $item->id),
                    ];
                });
            
            $results = array_merge($results, $visas->toArray());
        }
        
        return response()->json([
            'results' => $results,
            'total' => count($results),
            'query' => $request->input('q'),
        ]);
    }
    
    /**
     * Show search results page
     */
    public function results(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');
        
        return view('public.pages.search', compact('query', 'type'));
    }
}
