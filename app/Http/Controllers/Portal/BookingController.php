<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->customer->bookings()
            ->with('passengers')
            ->latest()
            ->paginate(10);

        return view('portal.bookings.index', compact('bookings'));
    }

    public function show(int $id)
    {
        $booking = Booking::where('customer_id', auth()->user()->customer->id)
            ->with(['passengers', 'payments', 'customer.user'])
            ->findOrFail($id);

        return view('portal.bookings.show', compact('booking'));
    }
}
