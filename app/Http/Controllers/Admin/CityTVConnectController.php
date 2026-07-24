<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CityTVConnect;
use Illuminate\Http\Request;

class CityTVConnectController extends Controller
{
    /**
     * Display a listing of branches.
     */
    public function index()
    {
        $branches = CityTVConnect::orderBy('name')->paginate(15);
        return view('admin.city-tv-connect.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        return view('admin.city-tv-connect.create');
    }

    /**
     * Store a newly created branch.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:city_tv_connects,serial_number',
            'password' => 'required|string',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer',
            'status' => 'required|in:active,inactive,error',
            'notes' => 'nullable|string',
        ]);

        CityTVConnect::create($validated);

        return redirect()->route('admin.city-tv-connect.index')
            ->with('success', 'Branch added successfully!');
    }

    /**
     * Display the specified branch.
     */
    public function show(CityTVConnect $cityTVConnect)
    {
        return view('admin.city-tv-connect.show', compact('cityTVConnect'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(CityTVConnect $cityTVConnect)
    {
        return view('admin.city-tv-connect.edit', compact('cityTVConnect'));
    }

    /**
     * Update the specified branch.
     */
    public function update(Request $request, CityTVConnect $cityTVConnect)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:city_tv_connects,serial_number,' . $cityTVConnect->id,
            'password' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer',
            'status' => 'required|in:active,inactive,error',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $cityTVConnect->update($validated);

        return redirect()->route('admin.city-tv-connect.index')
            ->with('success', 'Branch updated successfully!');
    }

    /**
     * Remove the specified branch.
     */
    public function destroy(CityTVConnect $cityTVConnect)
    {
        $cityTVConnect->delete();

        return redirect()->route('admin.city-tv-connect.index')
            ->with('success', 'Branch deleted successfully!');
    }

    /**
     * Secret camera monitoring page - hidden page for live camera feeds.
     */
    public function cameras()
    {
        $branches = CityTVConnect::where('status', 'active')->orderBy('name')->get();
        return view('admin.city-tv-connect.cameras', compact('branches'));
    }
}
