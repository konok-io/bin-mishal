<?php

namespace App\Http\Controllers\Admin\Cargo;

use App\Http\Controllers\Controller;
use App\Models\Cargo\CargoPackage;
use Illuminate\Http\Request;

class CargoPackageController extends Controller
{
    public function index()
    {
        $packages = CargoPackage::orderBy('sort_order')->get();
        return view('admin.cargo.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => 'required|string|max:20|unique:cargo_packages,code',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        CargoPackage::create($validated);

        return redirect()->back()->with('success', 'Package created successfully');
    }

    public function update(Request $request, CargoPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => 'required|string|max:20|unique:cargo_packages,code,' . $package->id,
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $package->update($validated);

        return redirect()->back()->with('success', 'Package updated successfully');
    }

    public function destroy(CargoPackage $package)
    {
        if ($package->cargos()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete package with associated cargo bookings');
        }

        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully');
    }
}
