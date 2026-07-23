<?php

namespace App\Http\Controllers\Admin\Cargo;

use App\Http\Controllers\Controller;
use App\Models\Cargo\CargoCity;
use App\Models\Cargo\CargoZone;
use Illuminate\Http\Request;

class CargoZoneController extends Controller
{
    public function index(CargoCity $city)
    {
        $zones = $city->zones()->orderBy('sort_order')->get();
        return view('admin.cargo.zones.index', compact('city', 'zones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cargo_cities,id',
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'delivery_charge' => 'nullable|numeric|min:0',
            'min_delivery_days' => 'nullable|integer|min:0',
            'max_delivery_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        CargoZone::create($validated);

        return redirect()->back()->with('success', 'Zone created successfully');
    }

    public function update(Request $request, CargoZone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'delivery_charge' => 'nullable|numeric|min:0',
            'min_delivery_days' => 'nullable|integer|min:0',
            'max_delivery_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $zone->update($validated);

        return redirect()->back()->with('success', 'Zone updated successfully');
    }

    public function destroy(CargoZone $zone)
    {
        $zone->delete();
        return redirect()->back()->with('success', 'Zone deleted successfully');
    }
}
