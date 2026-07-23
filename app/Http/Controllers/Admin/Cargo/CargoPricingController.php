<?php

namespace App\Http\Controllers\Admin\Cargo;

use App\Http\Controllers\Controller;
use App\Models\Cargo\CargoCity;
use App\Models\Cargo\CargoPricing;
use App\Models\Cargo\CargoType;
use Illuminate\Http\Request;

class CargoPricingController extends Controller
{
    public function index()
    {
        $pricings = CargoPricing::with(['cargoType', 'originCity', 'destinationCity'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $cargoTypes = CargoType::where('is_active', true)->get();
        $cities = CargoCity::where('is_active', true)->get();
        
        return view('admin.cargo.pricing.index', compact('pricings', 'cargoTypes', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cargo_type_id' => 'nullable|exists:cargo_types,id',
            'origin_city_id' => 'nullable|exists:cargo_cities,id',
            'destination_city_id' => 'nullable|exists:cargo_cities,id',
            'pricing_type' => 'required|in:weight,package,volumetric',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'price_per_kg' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        CargoPricing::create($validated);

        return redirect()->back()->with('success', 'Pricing created successfully');
    }

    public function update(Request $request, CargoPricing $pricing)
    {
        $validated = $request->validate([
            'cargo_type_id' => 'nullable|exists:cargo_types,id',
            'origin_city_id' => 'nullable|exists:cargo_cities,id',
            'destination_city_id' => 'nullable|exists:cargo_cities,id',
            'pricing_type' => 'required|in:weight,package,volumetric',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'price_per_kg' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $pricing->update($validated);

        return redirect()->back()->with('success', 'Pricing updated successfully');
    }

    public function destroy(CargoPricing $pricing)
    {
        $pricing->delete();
        return redirect()->back()->with('success', 'Pricing deleted successfully');
    }
}
