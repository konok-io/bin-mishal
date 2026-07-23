<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialApiController extends Controller
{
    /**
     * Get all active testimonials
     */
    public function index(Request $request): JsonResponse
    {
        $query = Testimonial::where('is_active', true);

        // Filter by service type
        if ($request->has('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $testimonials = $query->orderBy('sort_order')
            ->orderByDesc('is_featured')
            ->get();

        return response()->json($testimonials);
    }

    /**
     * Get featured testimonials
     */
    public function featured(): JsonResponse
    {
        $testimonials = Testimonial::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return response()->json($testimonials);
    }

    /**
     * Get testimonials by service type
     */
    public function byService(string $serviceType): JsonResponse
    {
        $testimonials = Testimonial::where('is_active', true)
            ->where('service_type', $serviceType)
            ->orderBy('sort_order')
            ->get();

        return response()->json($testimonials);
    }

    /**
     * Get average rating
     */
    public function rating(): JsonResponse
    {
        $stats = Testimonial::where('is_active', true)
            ->selectRaw('AVG(rating) as average_rating, COUNT(*) as total_reviews')
            ->first();

        return response()->json([
            'average_rating' => round($stats->average_rating ?? 0, 1),
            'total_reviews' => $stats->total_reviews ?? 0,
        ]);
    }
}
