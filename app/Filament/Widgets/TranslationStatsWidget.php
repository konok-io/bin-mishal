<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Translation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TranslationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Translation::count();
        $complete = Translation::where('status', 'complete')->count();
        $missingBn = Translation::where('status', 'missing_bn')->count();
        $missingEn = Translation::where('status', 'missing_en')->count();
        $missingAr = Translation::where('status', 'missing_ar')->count();
        $needsReview = Translation::where('status', 'needs_review')->count();
        $incomplete = $total - $complete;

        return [
            Stat::make('Total Keys', $total)
                ->description('Translation keys in database')
                ->icon('heroicon-o-language'),

            Stat::make('Complete', $complete)
                ->description(number_format(($complete / max($total, 1)) * 100, 1) . '% complete')
                ->color('success'),

            Stat::make('Missing Translations', $incomplete)
                ->description("BN: {$missingBn} | EN: {$missingEn} | AR: {$missingAr}")
                ->color($incomplete > 0 ? 'danger' : 'success'),
        ];
    }
}
