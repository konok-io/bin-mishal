<?php

namespace App\Http\Controllers\Admin;

use App\Models\UmrahPackage;
use Illuminate\Http\Request;

class UmrahController extends Controller
{
    public function index(Request $request)
    {
        $query = UmrahPackage::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $packages = $query->latest()->paginate(15);
        return view('admin.umrah.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.umrah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'makkah_hotel' => 'nullable|string|max:255',
            'makkah_nights' => 'nullable|integer|min:1',
            'madinah_hotel' => 'nullable|string|max:255',
            'madinah_nights' => 'nullable|integer|min:1',
            'price_double' => 'nullable|numeric|min:0',
        ]);

        UmrahPackage::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => \Illuminate\Support\Str::slug($request->title) . '-' . time(),
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_days ?? 7,
            'makkah_hotel' => $request->makkah_hotel,
            'makkah_nights' => $request->makkah_nights ?? 3,
            'madinah_hotel' => $request->madinah_hotel,
            'madinah_nights' => $request->madinah_nights ?? 3,
            'price_double' => $request->price_double,
            'status' => 'active',
        ]);

        return redirect()->route('admin.umrah.index')->with('success', 'Package created');
    }

    public function show(int $id)
    {
        $package = UmrahPackage::findOrFail($id);
        return view('admin.umrah.show', compact('package'));
    }

    public function edit(int $id)
    {
        $package = UmrahPackage::findOrFail($id);
        return view('admin.umrah.edit', compact('package'));
    }

    public function update(Request $request, int $id)
    {
        $package = UmrahPackage::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'price_double' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        $package->update($request->only(['title', 'description', 'duration_days', 'makkah_hotel', 'makkah_nights', 'madinah_hotel', 'madinah_nights', 'price_double', 'status']));

        return redirect()->back()->with('success', 'Package updated');
    }
}
