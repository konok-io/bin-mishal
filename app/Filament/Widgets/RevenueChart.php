<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Cargo\Cargo;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Overview (Last 6 Months)';

    protected function getData(): array
    {
        $months = [];
        $bookingRevenue = [];
        $cargoRevenue = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');

            $bookingRevenue[] = Booking::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->whereIn('payment_status', ['paid', 'completed'])
                ->sum('total_amount') ?? 0;

            $cargoRevenue[] = Cargo::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('payment_status', 'paid')
                ->sum('total_amount') ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Booking Revenue',
                    'data' => $bookingRevenue,
                    'borderColor' => '#059669',
                    'backgroundColor' => 'rgba(5, 150, 105, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Cargo Revenue',
                    'data' => $cargoRevenue,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
