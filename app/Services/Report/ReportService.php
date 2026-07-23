<?php

declare(strict_types=1);

namespace App\Services\Report;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\VisaApplication;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    /**
     * Revenue report by date range
     */
    public function revenueReport(Carbon $startDate, Carbon $endDate, string $groupBy = 'day'): array
    {
        $query = Payment::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate]);

        if ($groupBy === 'day') {
            $data = $query->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )->groupBy('date')->get();
        } else {
            $data = $query->select(
                DB::raw('YEAR(paid_at) as year'),
                DB::raw('MONTH(paid_at) as month'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )->groupBy('year', 'month')->get();
        }

        return [
            'period' => ['start' => $startDate->toDateString(), 'end' => $endDate->toDateString()],
            'group_by' => $groupBy,
            'total' => $data->sum('total'),
            'count' => $data->sum('count'),
            'data' => $data,
        ];
    }

    /**
     * Booking statistics
     */
    public function bookingStatistics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = Booking::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'total' => $query->count(),
            'by_status' => [
                'pending' => (clone $query)->where('booking_status', 'pending')->count(),
                'confirmed' => (clone $query)->where('booking_status', 'confirmed')->count(),
                'issued' => (clone $query)->where('booking_status', 'issued')->count(),
                'cancelled' => (clone $query)->where('booking_status', 'cancelled')->count(),
            ],
            'by_type' => [
                'ticket' => (clone $query)->where('booking_type', 'ticket')->count(),
                'umrah' => (clone $query)->where('booking_type', 'umrah')->count(),
                'visa' => (clone $query)->where('booking_type', 'visa')->count(),
                'package' => (clone $query)->where('booking_type', 'package')->count(),
            ],
            'total_revenue' => (clone $query)->where('booking_status', 'issued')->sum('total_amount'),
            'total_received' => (clone $query)->where('booking_status', 'issued')->sum('paid_amount'),
        ];
    }

    /**
     * Customer statistics
     */
    public function customerStatistics(): array
    {
        return [
            'total' => Customer::count(),
            'active' => Customer::whereHas('user', fn($q) => $q->where('status', 'active'))->count(),
            'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            'new_this_week' => Customer::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'by_source' => Customer::select('source', DB::raw('COUNT(*) as count'))
                ->groupBy('source')
                ->pluck('count', 'source'),
            'top_customers' => Customer::orderBy('lifetime_value', 'desc')
                ->limit(10)
                ->with('user')
                ->get()
                ->map(fn($c) => [
                    'name' => $c->user->name,
                    'lifetime_value' => $c->lifetime_value,
                    'total_bookings' => $c->total_bookings,
                ]),
        ];
    }

    /**
     * Lead conversion report
     */
    public function leadConversionReport(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = Lead::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $total = $query->count();
        $converted = (clone $query)->where('status', 'converted')->count();

        return [
            'total_leads' => $total,
            'converted' => $converted,
            'lost' => (clone $query)->where('status', 'lost')->count(),
            'new' => (clone $query)->where('status', 'new')->count(),
            'contacted' => (clone $query)->where('status', 'contacted')->count(),
            'qualified' => (clone $query)->where('status', 'qualified')->count(),
            'conversion_rate' => $total > 0 ? round(($converted / $total) * 100, 2) : 0,
            'by_source' => Lead::select('source', DB::raw('COUNT(*) as count'))
                ->groupBy('source')
                ->pluck('count', 'source'),
        ];
    }

    /**
     * Visa application report
     */
    public function visaReport(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = VisaApplication::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'total' => $query->count(),
            'by_status' => [
                'draft' => (clone $query)->where('status', 'draft')->count(),
                'submitted' => (clone $query)->where('status', 'submitted')->count(),
                'under_review' => (clone $query)->where('status', 'under_review')->count(),
                'approved' => (clone $query)->where('status', 'approved')->count(),
                'rejected' => (clone $query)->where('status', 'rejected')->count(),
                'delivered' => (clone $query)->where('status', 'delivered')->count(),
            ],
            'total_revenue' => (clone $query)->whereIn('status', ['approved', 'delivered'])->sum('total_amount'),
            'pending_count' => (clone $query)->whereIn('status', ['submitted', 'under_review', 'government_processing'])->count(),
        ];
    }

    /**
     * Daily sales report
     */
    public function dailySalesReport(): array
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        return [
            'today' => [
                'bookings' => Booking::whereDate('created_at', $today)->count(),
                'revenue' => Payment::whereDate('paid_at', $today)->where('status', 'completed')->sum('amount'),
                'new_customers' => Customer::whereDate('created_at', $today)->count(),
                'new_leads' => Lead::whereDate('created_at', $today)->count(),
            ],
            'yesterday' => [
                'bookings' => Booking::whereDate('created_at', $yesterday)->count(),
                'revenue' => Payment::whereDate('paid_at', $yesterday)->where('status', 'completed')->sum('amount'),
                'new_customers' => Customer::whereDate('created_at', $yesterday)->count(),
                'new_leads' => Lead::whereDate('created_at', $yesterday)->count(),
            ],
            'this_month' => [
                'bookings' => Booking::whereMonth('created_at', now()->month)->count(),
                'revenue' => Payment::whereMonth('paid_at', now()->month)->where('status', 'completed')->sum('amount'),
                'new_customers' => Customer::whereMonth('created_at', now()->month)->count(),
            ],
        ];
    }

    /**
     * Export to CSV format
     */
    public function exportToArray(array $data, array $columns): array
    {
        $export = [array_keys($columns)];

        foreach ($data as $row) {
            $exportRow = [];
            foreach ($columns as $key => $label) {
                $exportRow[] = data_get($row, $key, '');
            }
            $export[] = $exportRow;
        }

        return $export;
    }
}
