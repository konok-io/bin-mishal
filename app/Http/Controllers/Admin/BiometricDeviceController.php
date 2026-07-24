<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiometricDevice;
use App\Models\Branch;
use Illuminate\Http\Request;

class BiometricDeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = BiometricDevice::with('branch');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('device_id', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('branch_id') && $request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        $devices = $query->orderBy('created_at', 'desc')->paginate(20);
        $branches = Branch::orderBy('name')->get();
        $statuses = BiometricDevice::STATUSES;
        $brands = BiometricDevice::BRANDS;

        return view('admin.biometric-devices.index', compact('devices', 'branches', 'statuses', 'brands'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        $statuses = BiometricDevice::STATUSES;
        $brands = BiometricDevice::BRANDS;
        $syncMethods = BiometricDevice::SYNC_METHODS;
        return view('admin.biometric-devices.create', compact('branches', 'statuses', 'brands', 'syncMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'device_id' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer|min:1|max:65535',
            'comm_key' => 'nullable|string|max:255',
            'sync_method' => 'required|in:webhook,polling,manual,csv',
            'webhook_url' => 'nullable|url',
            'api_key' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,maintenance,offline',
            'sync_interval' => 'nullable|integer|min:5',
            'notes' => 'nullable|string',
        ]);

        BiometricDevice::create($validated);

        return redirect()->route('admin.biometric-devices.index')
            ->with('success', 'Biometric device created successfully.');
    }

    public function show(BiometricDevice $biometricDevice)
    {
        $biometricDevice->load('branch');
        return view('admin.biometric-devices.show', compact('biometricDevice'));
    }

    public function edit(BiometricDevice $biometricDevice)
    {
        $branches = Branch::orderBy('name')->get();
        $statuses = BiometricDevice::STATUSES;
        $brands = BiometricDevice::BRANDS;
        $syncMethods = BiometricDevice::SYNC_METHODS;
        return view('admin.biometric-devices.edit', compact('biometricDevice', 'branches', 'statuses', 'brands', 'syncMethods'));
    }

    public function update(Request $request, BiometricDevice $biometricDevice)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'device_id' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer|min:1|max:65535',
            'comm_key' => 'nullable|string|max:255',
            'sync_method' => 'required|in:webhook,polling,manual,csv',
            'webhook_url' => 'nullable|url',
            'api_key' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,maintenance,offline',
            'sync_interval' => 'nullable|integer|min:5',
            'notes' => 'nullable|string',
        ]);

        $biometricDevice->update($validated);

        return redirect()->route('admin.biometric-devices.index')
            ->with('success', 'Biometric device updated successfully.');
    }

    public function destroy(BiometricDevice $biometricDevice)
    {
        $biometricDevice->delete();

        return redirect()->route('admin.biometric-devices.index')
            ->with('success', 'Biometric device deleted successfully.');
    }
}
