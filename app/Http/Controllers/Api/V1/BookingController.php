<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\FlightRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Booking::with(['customer.user', 'issuedBy', 'passengers']);

        if ($request->has('status')) {
            $query->where('booking_status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($bookings);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_type' => 'required|in:ticket,umrah,visa,package',
            'passenger_count' => 'required|integer|min:1|max:10',
            'total_amount' => 'required|numeric|min:0',
            'passengers' => 'required|array|min:1',
            'passengers.*.first_name' => 'required|string|max:100',
            'passengers.*.last_name' => 'required|string|max:100',
            'passengers.*.title' => 'nullable|string|max:20',
            'passengers.*.gender' => 'nullable|in:male,female',
            'passengers.*.dob' => 'nullable|date',
            'passengers.*.passenger_type' => 'nullable|in:adult,child,infant',
            'passengers.*.passport_no' => 'nullable|string|max:50',
            'passengers.*.passport_expiry' => 'nullable|date',
            'passengers.*.nationality' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            $booking = Booking::create([
                'booking_no' => Booking::generateNo(),
                'customer_id' => $request->customer_id,
                'booking_type' => $request->booking_type,
                'passenger_count' => $request->passenger_count,
                'total_amount' => $request->total_amount,
                'paid_amount' => 0,
                'due_amount' => $request->total_amount,
                'booking_status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($request->passengers as $passengerData) {
                $booking->passengers()->create($passengerData);
            }

            DB::commit();

            return $this->success(
                $booking->load('customer.user', 'passengers'),
                'Booking created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create booking: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $booking = Booking::with(['customer.user', 'issuedBy', 'passengers', 'flightQuote.airline'])->findOrFail($id);

        return $this->success($booking);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'booking_status' => 'nullable|in:pending,confirmed,issued,cancelled,refunded',
            'pnr' => 'nullable|string|max:20',
            'ticket_file' => 'nullable|string|max:500',
        ]);

        $booking->update($request->only(['booking_status', 'pnr', 'ticket_file']));

        return $this->success($booking->load('customer.user', 'passengers'), 'Booking updated successfully');
    }

    public function issue(Request $request, int $id): JsonResponse
    {
        $booking = Booking::findOrFail($id);

        if ($booking->booking_status === 'issued') {
            return $this->error('Booking is already issued');
        }

        $booking->issue($request->user());

        return $this->success($booking->load('issuedBy'), 'Booking issued successfully');
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->booking_status === 'cancelled') {
            return $this->error('Booking is already cancelled');
        }

        $booking->cancel($request->reason);

        return $this->success($booking, 'Booking cancelled successfully');
    }

    public function addPayment(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->addPayment((float) $request->amount);

        return $this->success($booking, 'Payment added successfully');
    }

    public function passengers(int $id): JsonResponse
    {
        $booking = Booking::findOrFail($id);
        $passengers = $booking->passengers;

        return $this->success($passengers);
    }

    public function updatePassenger(Request $request, int $bookingId, int $passengerId): JsonResponse
    {
        $passenger = Passenger::where('booking_id', $bookingId)->findOrFail($passengerId);

        $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'title' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'passenger_type' => 'nullable|in:adult,child,infant',
            'passport_no' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'seat_preference' => 'nullable|string|max:50',
            'meal_preference' => 'nullable|string|max:50',
        ]);

        $passenger->update($request->only([
            'first_name', 'last_name', 'title', 'gender', 'dob',
            'passenger_type', 'passport_no', 'passport_expiry',
            'nationality', 'seat_preference', 'meal_preference'
        ]));

        return $this->success($passenger, 'Passenger updated successfully');
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('booking_status', 'pending')->count(),
            'issued' => Booking::where('booking_status', 'issued')->count(),
            'cancelled' => Booking::where('booking_status', 'cancelled')->count(),
            'unpaid' => Booking::where('payment_status', 'unpaid')->count(),
            'partial' => Booking::where('payment_status', 'partial')->count(),
            'total_revenue' => Booking::where('booking_status', 'issued')->sum('total_amount'),
            'total_collected' => Booking::sum('paid_amount'),
            'total_due' => Booking::sum('due_amount'),
        ];

        return $this->success($stats);
    }
}
