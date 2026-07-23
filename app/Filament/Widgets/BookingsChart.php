<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class BookingsChart extends ChartWidget
{
    protected static ?string $heading = 'Bookings by Service (This Month)';

    protected function getData(): array
    {
        $bookings = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('service_type, COUNT(*) as count')
            ->groupBy('service_type')
            ->pluck('count', 'service_type');

        $labels = $bookings->keys()->map(function ($type) {
            return ucfirst(str_replace('_', ' ', $type));
        })->toArray();

        $colors = [
            '#059669', // green - flight
            '#3B82F6', // blue - umrah
            '#8B5CF6', // purple - visa
            '#F59E0B', // amber - cargo
            '#EC4899', // pink - appointment
            '#06B6D4', // cyan - investor
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $bookings->values()->toArray(),
                    'backgroundColor' => array_slice($colors, 0, count($bookings)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
