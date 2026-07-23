<?php

declare(strict_types=1);

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public const LOCATION_HEADER = 'header';
    public const LOCATION_FOOTER_COL1 = 'footer_col1';
    public const LOCATION_FOOTER_COL2 = 'footer_col2';
    public const LOCATION_FOOTER_COL3 = 'footer_col3';
    public const LOCATION_FOOTER_BOTTOM = 'footer_bottom';
    public const LOCATION_MOBILE = 'mobile';
    public const LOCATION_TOP_BAR = 'top_bar';
    public const LOCATION_MEGA_SERVICES = 'mega_services';
    public const LOCATION_MEGA_ABOUT = 'mega_about';

    public const LOCATIONS = [
        self::LOCATION_HEADER => 'Header Navigation',
        self::LOCATION_FOOTER_COL1 => 'Footer Column 1',
        self::LOCATION_FOOTER_COL2 => 'Footer Column 2',
        self::LOCATION_FOOTER_COL3 => 'Footer Column 3',
        self::LOCATION_FOOTER_BOTTOM => 'Footer Bottom Bar',
        self::LOCATION_MOBILE => 'Mobile Menu',
        self::LOCATION_TOP_BAR => 'Top Bar',
        self::LOCATION_MEGA_SERVICES => 'Mega Menu Services',
        self::LOCATION_MEGA_ABOUT => 'Mega Menu About',
    ];

    private const CACHE_TTL = 3600;

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    public function getTree(): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "menu_tree_{$this->id}_" . app()->getLocale();

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return $this->items()
                ->where('status', true)
                ->with(['children' => fn($q) => $q->where('status', true)->orderBy('order')])
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
        });
    }

    public static function getByLocation(string $location): ?self
    {
        $cacheKey = "menu_location_{$location}_" . app()->getLocale();

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($location) {
            return self::active()->byLocation($location)->first();
        });
    }

    public static function clearCache(?int $menuId = null): void
    {
        if ($menuId) {
            foreach (['bn', 'en', 'ar'] as $locale) {
                Cache::forget("menu_tree_{$menuId}_{$locale}");
            }
        }

        foreach (array_keys(self::LOCATIONS) as $location) {
            foreach (['bn', 'en', 'ar'] as $locale) {
                Cache::forget("menu_location_{$location}_{$locale}");
            }
        }
    }

    protected static function booted(): void
    {
        static::updated(fn(Menu $menu) => self::clearCache($menu->id));
        static::deleted(fn(Menu $menu) => self::clearCache($menu->id));
    }
}
