<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\VisaApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'customers' => [
                'total' => Customer::count(),
                'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            ],
            'bookings' => [
                'total' => Booking::count(),
                'pending' => Booking::where('booking_status', 'pending')->count(),
                'issued' => Booking::where('booking_status', 'issued')->count(),
                'total_revenue' => Booking::where('booking_status', 'issued')->sum('total_amount'),
            ],
            'visas' => [
                'total' => VisaApplication::count(),
                'pending' => VisaApplication::pending()->count(),
                'approved' => VisaApplication::where('status', 'approved')->count(),
            ],
            'leads' => [
                'total' => Lead::count(),
                'new' => Lead::where('status', 'new')->count(),
                'converted' => Lead::where('status', 'converted')->count(),
                'due_today' => Lead::dueToday()->count(),
            ],
            'payments' => [
                'total_received' => Payment::where('status', 'completed')->sum('amount'),
                'pending' => Payment::where('status', 'pending')->count(),
            ],
        ];

        $recentBookings = Booking::with(['customer.user'])
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = Payment::with(['customer.user'])
            ->where('status', 'completed')
            ->latest('paid_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentBookings', 'recentPayments'));
    }
}
