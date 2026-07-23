<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    /**
     * Get all active FAQs
     */
    public function index(Request $request): JsonResponse
    {
        $query = Faq::where('is_active', true);

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by service type
        if ($request->has('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $faqs = $query->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return response()->json($faqs);
    }

    /**
     * Get FAQs grouped by category
     */
    public function byCategory(): JsonResponse
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return response()->json($faqs);
    }

    /**
     * Get FAQs for a specific service
     */
    public function forService(string $serviceType): JsonResponse
    {
        $faqs = Faq::where('is_active', true)
            ->where(function ($query) use ($serviceType) {
                $query->where('category', $serviceType)
                    ->orWhere('service_type', $serviceType);
            })
            ->orderBy('sort_order')
            ->get();

        return response()->json($faqs);
    }
}
