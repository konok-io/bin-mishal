<?php

namespace App\Console\Commands;

use App\Models\HeroTab;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearHeroTabCache extends Command
{
    protected $signature = 'cache:herotabs {--all : Clear all related caches}';
    protected $description = 'Clear HeroTab related caches';

    public function handle(): int
    {
        $this->info('Clearing HeroTab caches...');

        // Clear all locale-specific caches
        $locales = ['bn', 'en', 'ar'];
        foreach ($locales as $locale) {
            Cache::forget("hero_tabs_{$locale}");
            Cache::forget("hero_tabs_nav_{$locale}");
            Cache::forget("header_nav_tabs_{$locale}");
            Cache::forget("services_active_tabs_{$locale}");
            Cache::forget("hero_active_tabs_{$locale}");
            Cache::forget("dynamic_hero_tabs_{$locale}");
            Cache::forget("welcome_booking_tabs_{$locale}");
        }

        // Also clear legacy/without-locale cache keys
        Cache::forget("header_nav_tabs");
        Cache::forget("services_active_tabs");
        Cache::forget("hero_active_tabs");
        Cache::forget("dynamic_hero_tabs");

        // Also trigger model cache clearing
        HeroTab::clearCache();

        $this->info('HeroTab caches cleared successfully!');

        return Command::SUCCESS;
    }
}
