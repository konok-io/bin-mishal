<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\VisaApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends ApiController
{
    public function index(): JsonResponse
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

        return $this->success($stats);
    }

    public function recentBookings(): JsonResponse
    {
        $bookings = Booking::with(['customer.user'])
            ->latest()
            ->limit(10)
            ->get();

        return $this->success($bookings);
    }

    public function recentPayments(): JsonResponse
    {
        $payments = Payment::with(['customer.user'])
            ->where('status', 'completed')
            ->latest('paid_at')
            ->limit(10)
            ->get();

        return $this->success($payments);
    }

    public function monthlyRevenue(): JsonResponse
    {
        $revenue = Booking::where('booking_status', 'issued')
            ->whereYear('issue_date', now()->year)
            ->selectRaw('MONTH(issue_date) as month, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $this->success($revenue);
    }

    public function bookingStats(): JsonResponse
    {
        $stats = Booking::selectRaw('booking_type, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('booking_type')
            ->get();

        return $this->success($stats);
    }

    public function paymentStats(): JsonResponse
    {
        $stats = Payment::where('status', 'completed')
            ->selectRaw('DATE(paid_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->whereDate('paid_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->success($stats);
    }
}
