<?php

namespace App\Http\Controllers\Admin\Cargo;

use App\Http\Controllers\Controller;
use App\Models\Cargo\Cargo;
use App\Models\Cargo\CargoCity;
use App\Models\Cargo\CargoType;
use App\Models\Cargo\CargoPackage;
use App\Services\Cargo\CargoService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CargoController extends Controller
{
    /**
     * Display cargo bookings list
     */
    public function index(Request $request)
    {
        $query = Cargo::with(['cargoType', 'receiverZone', 'trackingHistory']);

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('tracking_number', 'like', "%{$request->search}%")
                  ->orWhere('sender_name', 'like', "%{$request->search}%")
                  ->orWhere('receiver_name', 'like', "%{$request->search}%");
            });
        }
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $cargos = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $statuses = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'collected' => 'Collected',
            'warehouse' => 'Warehouse',
            'in_transit' => 'In Transit',
            'customs' => 'Customs',
            'bd_hub' => 'BD Hub',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];

        return view('admin.cargo.index', compact('cargos', 'statuses'));
    }

    /**
     * Show cargo details
     */
    public function show(Cargo $cargo)
    {
        $cargo->load(['cargoType', 'cargoPackage', 'receiverZone', 'trackingHistory', 'branch']);
        return view('admin.cargo.show', compact('cargo'));
    }

    /**
     * Create new cargo booking
     */
    public function create()
    {
        $cargoTypes = CargoType::where('is_active', true)->orderBy('sort_order')->get();
        $packages = CargoPackage::where('is_active', true)->orderBy('sort_order')->get();
        $cities = CargoCity::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.cargo.create', compact('cargoTypes', 'packages', 'cities'));
    }

    /**
     * Store new cargo booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'sender_email' => 'nullable|email',
            'sender_address' => 'required|string',
            'sender_city' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'nullable|email',
            'receiver_address' => 'required|string',
            'receiver_city' => 'required|string|max:255',
            'cargo_type_id' => 'nullable|exists:cargo_types,id',
            'cargo_package_id' => 'nullable|exists:cargo_packages,id',
            'weight' => 'nullable|numeric|min:0',
            'declared_value' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:1',
            'special_instructions' => 'nullable|string',
        ]);

        // Calculate pricing
        $pricing = CargoService::calculatePrice(
            null,
            null,
            $validated['cargo_type_id'] ?? null,
            $validated['weight'] ?? 0,
            $validated['cargo_package_id'] ?? null
        );

        $cargo = CargoService::createCargo([
            ...$validated,
            'shipping_cost' => $pricing['base_price'],
            'vat_amount' => $pricing['vat_amount'],
            'total_amount' => $pricing['total'],
        ]);

        return redirect()->route('admin.cargo.show', $cargo->id)
            ->with('success', 'Cargo booking created successfully. Tracking: ' . $cargo->tracking_number);
    }

    /**
     * Update cargo status
     */
    public function updateStatus(Request $request, Cargo $cargo)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,collected,warehouse,in_transit,customs,bd_hub,out_for_delivery,delivered,cancelled,returned',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        CargoService::updateStatus(
            $cargo,
            $validated['status'],
            $validated['description'] ?? null,
            $validated['location'] ?? null
        );

        return redirect()->back()->with('success', 'Status updated successfully');
    }

    /**
     * Generate PDF invoice
     */
    public function invoice(Cargo $cargo)
    {
        $cargo->load(['cargoType', 'cargoPackage', 'receiverZone']);
        
        $pdf = Pdf::loadView('admin.cargo.invoice', compact('cargo'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("cargo-invoice-{$cargo->tracking_number}.pdf");
    }

    /**
     * Print shipping label
     */
    public function label(Cargo $cargo)
    {
        $cargo->load(['cargoType', 'receiverZone']);
        
        return view('admin.cargo.label', compact('cargo'));
    }

    /**
     * Export cargo list
     */
    public function export(Request $request)
    {
        $query = Cargo::with(['cargoType']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $cargos = $query->get();

        $csvData = [];
        $csvData[] = ['Tracking', 'Sender', 'Receiver', 'Type', 'Weight', 'Status', 'Amount', 'Date'];

        foreach ($cargos as $cargo) {
            $csvData[] = [
                $cargo->tracking_number,
                $cargo->sender_name,
                $cargo->receiver_name,
                $cargo->cargoType?->name ?? 'N/A',
                $cargo->weight,
                $cargo->status,
                $cargo->total_amount,
                $cargo->created_at->format('Y-m-d'),
            ];
        }

        $filename = 'cargo-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, $headers);
    }

    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        $stats = [
            'total' => Cargo::count(),
            'pending' => Cargo::where('status', 'pending')->count(),
            'in_transit' => Cargo::whereIn('status', ['confirmed', 'collected', 'warehouse', 'in_transit', 'customs', 'bd_hub', 'out_for_delivery'])->count(),
            'delivered' => Cargo::where('status', 'delivered')->count(),
            'cancelled' => Cargo::where('status', 'cancelled')->count(),
            'today' => Cargo::whereDate('created_at', today())->count(),
            'this_month' => Cargo::whereMonth('created_at', now()->month)->count(),
            'revenue' => Cargo::where('payment_status', 'paid')->sum('total_amount'),
            'pending_payment' => Cargo::where('payment_status', '!=', 'paid')->sum('total_amount'),
        ];

        $recentCargos = Cargo::with(['cargoType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.cargo.dashboard', compact('stats', 'recentCargos'));
    }
}
