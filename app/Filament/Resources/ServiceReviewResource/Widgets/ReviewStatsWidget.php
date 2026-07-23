<?php

declare(strict_types=1);

namespace App\Filament\Resources\ServiceReviewResource\Widgets;

use App\Models\ServiceReview;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewStatsWidget extends BaseWidget
{
    public function getStats(): array
    {
        $totalReviews = ServiceReview::count();
        $approvedReviews = ServiceReview::where('is_approved', true)->count();
        $pendingReviews = ServiceReview::where('is_approved', false)->count();
        $averageRating = ServiceReview::where('is_approved', true)->avg('rating') ?? 0;

        return [
            Stat::make('Total Reviews', $totalReviews)
                ->description('All reviews submitted')
                ->icon('heroicon-o-chat-bubble-left')
                ->color('info'),
            
            Stat::make('Approved', $approvedReviews)
                ->description('Published on site')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Pending', $pendingReviews)
                ->description('Awaiting approval')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Average Rating', number_format($averageRating, 1) . ' ⭐')
                ->description('From approved reviews')
                ->icon('heroicon-o-star')
                ->color('gray'),
        ];
    }
}
