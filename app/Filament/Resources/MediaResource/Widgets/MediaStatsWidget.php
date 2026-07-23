<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaResource\Widgets;

use App\Models\Media;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MediaStatsWidget extends BaseWidget
{
    public function getStats(): array
    {
        return [
            Stat::make('Total Files', Media::count())
                ->description('All uploaded media')
                ->icon('heroicon-o-document')
                ->color('info'),
            
            Stat::make('Images', Media::where('file_type', 'image')->count())
                ->description('Photos and graphics')
                ->icon('heroicon-o-photo')
                ->color('success'),
            
            Stat::make('Documents', Media::where('file_type', 'document')->count())
                ->description('PDFs and files')
                ->icon('heroicon-o-document-text')
                ->color('warning'),
            
            Stat::make('Storage Used', $this->formatBytes(Media::sum('file_size')))
                ->description('Total media size')
                ->icon('heroicon-o-cloud')
                ->color('gray'),
        ];
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
