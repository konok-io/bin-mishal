<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\UmrahPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmrahController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = UmrahPackage::query();

        if ($request->boolean('featured')) {
            $query->featured();
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $packages = $query->active()->latest()->paginate($this->perPage($request));

        return $this->paginate($packages);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'duration_nights' => 'nullable|integer|min:1',
            'makkah_hotel' => 'nullable|string|max:255',
            'makkah_hotel_stars' => 'nullable|integer|min:1|max:7',
            'makkah_distance_meters' => 'nullable|integer|min:0',
            'makkah_nights' => 'nullable|integer|min:1',
            'madinah_hotel' => 'nullable|string|max:255',
            'madinah_hotel_stars' => 'nullable|integer|min:1|max:7',
            'madinah_distance_meters' => 'nullable|integer|min:0',
            'madinah_nights' => 'nullable|integer|min:1',
            'transport_type' => 'nullable|string|max:100',
            'meal_plan' => 'nullable|in:none,breakfast,half_board,full_board',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'itinerary' => 'nullable|array',
            'price_quad' => 'nullable|numeric|min:0',
            'price_triple' => 'nullable|numeric|min:0',
            'price_double' => 'nullable|numeric|min:0',
            'price_single' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|string|max:500',
            'gallery' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();

        try {
            $package = UmrahPackage::create([
                'title' => $request->title,
                'title_bn' => $request->title_bn,
                'title_ar' => $request->title_ar,
                'description' => $request->description,
                'slug' => \Illuminate\Support\Str::slug($request->title) . '-' . time(),
                'duration_days' => $request->duration_days,
                'duration_nights' => $request->duration_nights,
                'makkah_hotel' => $request->makkah_hotel,
                'makkah_hotel_stars' => $request->makkah_hotel_stars ?? 3,
                'makkah_distance_meters' => $request->makkah_distance_meters,
                'makkah_nights' => $request->makkah_nights ?? 3,
                'madinah_hotel' => $request->madinah_hotel,
                'madinah_hotel_stars' => $request->madinah_hotel_stars ?? 3,
                'madinah_distance_meters' => $request->madinah_distance_meters,
                'madinah_nights' => $request->madinah_nights ?? 3,
                'transport_type' => $request->transport_type,
                'meal_plan' => $request->meal_plan ?? 'breakfast',
                'inclusions' => $request->inclusions,
                'exclusions' => $request->exclusions,
                'itinerary' => $request->itinerary,
                'price_quad' => $request->price_quad,
                'price_triple' => $request->price_triple,
                'price_double' => $request->price_double,
                'price_single' => $request->price_single,
                'child_price' => $request->child_price,
                'infant_price' => $request->infant_price,
                'featured_image' => $request->featured_image,
                'gallery' => $request->gallery,
                'is_featured' => $request->boolean('is_featured'),
                'status' => $request->status ?? 'active',
            ]);

            DB::commit();

            return $this->success($package, 'Umrah package created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create package: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $package = UmrahPackage::findOrFail($id);

        return $this->success($package);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $package = UmrahPackage::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'duration_nights' => 'nullable|integer|min:1',
            'makkah_hotel' => 'nullable|string|max:255',
            'makkah_hotel_stars' => 'nullable|integer|min:1|max:7',
            'makkah_distance_meters' => 'nullable|integer|min:0',
            'makkah_nights' => 'nullable|integer|min:1',
            'madinah_hotel' => 'nullable|string|max:255',
            'madinah_hotel_stars' => 'nullable|integer|min:1|max:7',
            'madinah_distance_meters' => 'nullable|integer|min:0',
            'madinah_nights' => 'nullable|integer|min:1',
            'transport_type' => 'nullable|string|max:100',
            'meal_plan' => 'nullable|in:none,breakfast,half_board,full_board',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'itinerary' => 'nullable|array',
            'price_quad' => 'nullable|numeric|min:0',
            'price_triple' => 'nullable|numeric|min:0',
            'price_double' => 'nullable|numeric|min:0',
            'price_single' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|string|max:500',
            'gallery' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        $package->update($request->only([
            'title', 'title_bn', 'title_ar', 'description',
            'duration_days', 'duration_nights',
            'makkah_hotel', 'makkah_hotel_stars', 'makkah_distance_meters', 'makkah_nights',
            'madinah_hotel', 'madinah_hotel_stars', 'madinah_distance_meters', 'madinah_nights',
            'transport_type', 'meal_plan',
            'inclusions', 'exclusions', 'itinerary',
            'price_quad', 'price_triple', 'price_double', 'price_single',
            'child_price', 'infant_price',
            'featured_image', 'gallery', 'is_featured', 'status'
        ]));

        return $this->success($package, 'Package updated successfully');
    }

    public function featured(): JsonResponse
    {
        $packages = UmrahPackage::featured()->active()->latest()->limit(6)->get();

        return $this->success($packages);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'min_nights' => 'nullable|integer|min:1',
            'hotel_stars' => 'nullable|integer|min:1|max:7',
        ]);

        $query = UmrahPackage::active();

        if ($request->has('min_price')) {
            $query->where('price_double', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price_double', '<=', $request->max_price);
        }

        if ($request->has('min_nights')) {
            $query->where('duration_nights', '>=', $request->min_nights);
        }

        if ($request->has('hotel_stars')) {
            $query->where('makkah_hotel_stars', '>=', $request->hotel_stars);
        }

        $packages = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($packages);
    }
}
