<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Branch;
use App\Models\VisaType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterDataController extends ApiController
{
    public function airlines(Request $request): JsonResponse
    {
        $query = Airline::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('iata_code', 'like', '%' . $request->search . '%');
        }

        $airlines = $query->active()->orderBy('name')->get();

        return $this->success($airlines);
    }

    public function airports(Request $request): JsonResponse
    {
        $query = Airport::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhere('iata_code', 'like', '%' . $request->search . '%');
        }

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        $airports = $query->active()->orderBy('city')->get();

        return $this->success($airports);
    }

    public function branches(Request $request): JsonResponse
    {
        $query = Branch::query();

        if ($request->boolean('main_only')) {
            $query->main();
        }

        $branches = $query->active()->orderBy('is_main', 'desc')->orderBy('name')->get();

        return $this->success($branches);
    }

    public function visaTypes(Request $request): JsonResponse
    {
        $query = VisaType::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        $types = $query->active()->orderBy('name')->get();

        return $this->success($types);
    }

    public function countries(): JsonResponse
    {
        $countries = Airport::active()
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return $this->success($countries);
    }

    public function cities(Request $request): JsonResponse
    {
        $request->validate([
            'country' => 'nullable|string|max:100',
        ]);

        $query = Airport::active()->orderBy('city');

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        $cities = $query->distinct()->pluck('city');

        return $this->success($cities);
    }
}
