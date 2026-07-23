<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = auth()->user()->customer->appointments()
            ->with('branch')
            ->latest()
            ->paginate(10);

        return view('portal.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $slots = AppointmentSlot::where('date', '>=', now())
            ->where('booked_count', '<', 'capacity')
            ->where('status', 'active')
            ->with('branch')
            ->get()
            ->groupBy('date');

        return view('portal.appointments.create', compact('slots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_id' => 'required|exists:appointment_slots,id',
            'purpose' => 'required|string|max:500',
        ]);

        $slot = AppointmentSlot::find($validated['slot_id']);

        $appointment = auth()->user()->customer->appointments()->create([
            'appointment_no' => Appointment::generateNo(),
            'appointment_slot_id' => $slot->id,
            'branch_id' => $slot->branch_id,
            'service_type' => $slot->service_type,
            'preferred_date' => $slot->date,
            'preferred_time' => $slot->start_time,
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        // Update slot count
        $slot->increment('booked_count');

        return redirect()->route('portal.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully.');
    }

    public function show(int $id)
    {
        $appointment = Appointment::where('customer_id', auth()->user()->customer->id)
            ->with(['branch', 'slot'])
            ->findOrFail($id);

        return view('portal.appointments.show', compact('appointment'));
    }
}
