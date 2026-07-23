<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Cargo\Cargo;
use App\Models\InvestorApplication;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->icon('heroicon-o-users'),

            Stat::make('New Users Today', User::where('created_at', '>=', $today)->count())
                ->description('Registered today')
                ->icon('heroicon-o-user-plus'),

            Stat::make('Total Bookings', Booking::count())
                ->description('All time bookings')
                ->icon('heroicon-o-ticket'),

            Stat::make('Bookings This Month', Booking::where('created_at', '>=', $thisMonth)->count())
                ->description('Current month')
                ->icon('heroicon-o-calendar'),

            Stat::make('Cargo Shipments', Cargo::count())
                ->description('Total shipments')
                ->icon('heroicon-o-truck'),

            Stat::make('Active Cargo', Cargo::whereIn('status', ['confirmed', 'collected', 'in_transit'])->count())
                ->description('In transit')
                ->icon('heroicon-o-arrow-right'),

            Stat::make('Investor Applications', InvestorApplication::count())
                ->description('Total applications')
                ->icon('heroicon-o-document-chart-bar'),

            Stat::make('Pending Applications', InvestorApplication::where('status', 'submitted')->count())
                ->description('Awaiting review')
                ->icon('heroicon-o-clock'),

            Stat::make('Blog Posts', Post::where('is_published', true)->count())
                ->description('Published articles')
                ->icon('heroicon-o-newspaper'),

            Stat::make('Page Views', Post::sum('view_count'))
                ->description('Total reads')
                ->icon('heroicon-o-eye'),
        ];
    }
}
