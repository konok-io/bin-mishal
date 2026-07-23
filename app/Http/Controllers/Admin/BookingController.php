<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer.user']);

        if ($request->has('status')) {
            $query->where('booking_status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('booking_no', 'like', "%{$search}%");
        }

        $bookings = $query->latest()->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        return view('admin.bookings.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_type' => 'required|in:ticket,umrah,visa,package',
            'total_amount' => 'required|numeric|min:0',
        ]);

        Booking::create([
            'booking_no' => Booking::generateNo(),
            'customer_id' => $request->customer_id,
            'booking_type' => $request->booking_type,
            'passenger_count' => 1,
            'total_amount' => $request->total_amount,
            'paid_amount' => 0,
            'due_amount' => $request->total_amount,
            'booking_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully');
    }

    public function show(int $id)
    {
        $booking = Booking::with(['customer.user', 'issuedBy', 'passengers'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function issue(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->issue(auth()->user());
        return redirect()->back()->with('success', 'Booking issued successfully');
    }

    public function cancel(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $booking = Booking::findOrFail($id);
        $booking->cancel($request->reason);
        return redirect()->back()->with('success', 'Booking cancelled');
    }
}
