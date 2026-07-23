<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Homepage with all sections
     */
    public function home(): View
    {
        return view('welcome');
    }

    /**
     * About page
     */
    public function about(): View
    {
        return view('public.pages.about');
    }

    /**
     * Contact page
     */
    public function contact(): View
    {
        return view('public.pages.contact');
    }

    /**
     * FAQs page
     */
    public function faqs(): View
    {
        return view('public.pages.faqs');
    }

    /**
     * Careers page
     */
    public function careers(): View
    {
        $jobs = \App\Models\Job::published()
            ->orderBy('is_featured', 'desc')
            ->orderBy('sort_order')
            ->limit(10)
            ->get();
            
        return view('frontend.careers.index', compact('jobs'));
    }

    /**
     * Services listing
     */
    public function services(): View
    {
        return view('public.pages.services');
    }

    /**
     * Umrah packages listing
     */
    public function umrah(): View
    {
        return view('public.pages.umrah');
    }

    /**
     * Single umrah package
     */
    public function umrahPackage(string $slug): View
    {
        return view('public.pages.umrah-package', compact('slug'));
    }

    /**
     * Visa services listing
     */
    public function visa(): View
    {
        return view('public.pages.visa');
    }

    /**
     * Single visa service
     */
    public function visaService(string $slug): View
    {
        return view('public.pages.visa-service', compact('slug'));
    }

    /**
     * Air ticket booking
     */
    public function airticket(): View
    {
        return view('public.pages.airticket');
    }

    /**
     * Hotel booking
     */
    public function hotel(): View
    {
        return view('public.pages.hotel');
    }

    /**
     * News listing
     */
    public function news(): View
    {
        return view('public.pages.news');
    }

    /**
     * Single news article
     */
    public function newsDetail(string $slug): View
    {
        return view('public.pages.news-detail', compact('slug'));
    }

    /**
     * Blog listing
     */
    public function blog(): View
    {
        $posts = \App\Models\Post::where('is_published', true)
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);
            
        $featuredPosts = \App\Models\Post::where('is_published', true)
            ->where('is_featured', true)
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
            
        $categories = \App\Models\PostCategory::where('is_active', true)
            ->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(8)
            ->get();
            
        return view('frontend.blog.index', compact('posts', 'featuredPosts', 'categories'));
    }

    /**
     * Single blog post
     */
    public function blogDetail(string $slug): View
    {
        $post = \App\Models\Post::where('slug', $slug)
            ->where('is_published', true)
            ->with(['category', 'author', 'tags', 'comments'])
            ->first();
        
        if (!$post) {
            abort(404);
        }
        
        // Increment view count
        $post->increment('view_count');
        
        // Get related posts by category
        $relatedPosts = \App\Models\Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category_id', $post->category_id)
                    ->orWhereHas('tags', function ($q) use ($post) {
                        $q->whereIn('post_tag_id', $post->tags->pluck('id'));
                    });
            })
            ->with(['category', 'author'])
            ->limit(3)
            ->get();
            
        return view('frontend.blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Labour Law section
     */
    public function labourLaw(): View
    {
        return view('public.pages.labour-law');
    }

    /**
     * Single labour law article
     */
    public function labourLawDetail(string $slug): View
    {
        return view('public.pages.labour-law-detail', compact('slug'));
    }

    /**
     * Visa status checker
     */
    public function visaChecker(): View
    {
        return view('public.pages.visa-checker');
    }

    /**
     * Order tracking
     */
    public function track(): View
    {
        return view('public.pages.track');
    }

    /**
     * Appointment booking
     */
    public function appointment(): View
    {
        return view('public.pages.appointment');
    }

    /**
     * Privacy Policy
     */
    public function privacyPolicy(): View
    {
        return view('public.pages.privacy-policy');
    }

    /**
     * Terms of Service
     */
    public function terms(): View
    {
        return view('public.pages.terms');
    }

    /**
     * Refund Policy
     */
    public function refundPolicy(): View
    {
        return view('public.pages.refund-policy');
    }

    /**
     * Cargo Service
     */
    public function cargo(): View
    {
        return view('public.pages.cargo');
    }

    /**
     * Cargo Tracking Result
     */
    public function trackCargo($trackingNumber)
    {
        $cargo = \App\Models\Cargo\Cargo::with(['cargoType', 'cargoPackage', 'receiverZone', 'trackingHistory'])
            ->where('tracking_number', $trackingNumber)
            ->first();

        if (!$cargo) {
            return redirect()->back()->with('error', 'Cargo not found with tracking number: ' . $trackingNumber);
        }

        return view('public.pages.cargo-tracking', compact('cargo'));
    }

    /**
     * Testimonials page
     */
    public function testimonials(): View
    {
        $testimonials = \App\Models\Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        return view('frontend.testimonials.index', compact('testimonials'));
    }

    /**
     * Single career detail
     */
    public function careerDetail(string $slug)
    {
        $job = \App\Models\Job::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$job) {
            abort(404);
        }

        return view('frontend.careers.show', compact('job'));
    }

    /**
     * Career application submission
     */
    public function careerApply(string $slug)
    {
        $job = \App\Models\Job::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$job) {
            abort(404);
        }

        // Handle form submission
        $validated = request()->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
            'consent' => 'required|accepted',
        ]);

        $application = new \App\Models\JobApplication();
        $application->job_id = $job->id;
        $application->full_name = $validated['full_name'];
        $application->email = $validated['email'];
        $application->phone = $validated['phone'];
        
        if (isset($validated['cv'])) {
            $path = request()->file('cv')->store('applications/cv', 'public');
            $application->cv_path = $path;
        }
        
        if (isset($validated['cover_letter'])) {
            $application->cover_letter = $validated['cover_letter'];
        }
        
        $application->status = 'received';
        $application->save();

        return redirect()->back()->with('success', 'Your application has been submitted successfully!');
    }
}
