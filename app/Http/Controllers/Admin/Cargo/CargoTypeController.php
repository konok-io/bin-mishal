<?php

namespace App\Http\Controllers\Admin\Cargo;

use App\Http\Controllers\Controller;
use App\Models\Cargo\CargoType;
use Illuminate\Http\Request;

class CargoTypeController extends Controller
{
    public function index()
    {
        $types = CargoType::orderBy('sort_order')->get();
        return view('admin.cargo.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        CargoType::create($validated);

        return redirect()->back()->with('success', 'Cargo type created successfully');
    }

    public function update(Request $request, CargoType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $type->update($validated);

        return redirect()->back()->with('success', 'Cargo type updated successfully');
    }

    public function destroy(CargoType $type)
    {
        if ($type->cargos()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete type with associated cargo bookings');
        }

        $type->delete();

        return redirect()->back()->with('success', 'Cargo type deleted successfully');
    }
}
