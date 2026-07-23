<?php

namespace App\Http\Controllers\Admin\Cargo;

use App\Http\Controllers\Controller;
use App\Models\Cargo\CargoCity;
use Illuminate\Http\Request;

class CargoCityController extends Controller
{
    public function index()
    {
        $cities = CargoCity::with('zones')->orderBy('name')->get();
        return view('admin.cargo.cities.index', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:10',
            'country_id' => 'nullable|exists:countries,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_saudi' => 'boolean',
            'is_bangladesh' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        CargoCity::create($validated);

        return redirect()->back()->with('success', 'City created successfully');
    }

    public function update(Request $request, CargoCity $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:10',
            'country_id' => 'nullable|exists:countries,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_saudi' => 'boolean',
            'is_bangladesh' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $city->update($validated);

        return redirect()->back()->with('success', 'City updated successfully');
    }

    public function destroy(CargoCity $city)
    {
        if ($city->zones()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete city with associated zones');
        }

        $city->delete();

        return redirect()->back()->with('success', 'City deleted successfully');
    }
}
