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
        return view('public.pages.blog');
    }

    /**
     * Single blog post
     */
    public function blogDetail(string $slug): View
    {
        return view('public.pages.blog-detail', compact('slug'));
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
}
