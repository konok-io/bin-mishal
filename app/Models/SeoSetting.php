<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'locale',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'robots',
        'schema_markup',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const PAGE_HOME = 'home';
    public const PAGE_ABOUT = 'about';
    public const PAGE_FLIGHT = 'flight';
    public const PAGE_UMRAH = 'umrah';
    public const PAGE_VISA = 'visa';
    public const PAGE_CARGO = 'cargo';
    public const PAGE_INVESTOR = 'investor';
    public const PAGE_CONTACT = 'contact';
    public const PAGE_BLOG = 'blog';
    public const PAGE_GALLERY = 'gallery';

    public const PAGES = [
        self::PAGE_HOME => 'Home',
        self::PAGE_ABOUT => 'About',
        self::PAGE_FLIGHT => 'Flight',
        self::PAGE_UMRAH => 'Umrah',
        self::PAGE_VISA => 'Visa',
        self::PAGE_CARGO => 'Cargo',
        self::PAGE_INVESTOR => 'Investor',
        self::PAGE_CONTACT => 'Contact',
        self::PAGE_BLOG => 'Blog',
        self::PAGE_GALLERY => 'Gallery',
    ];

    public const ROBOTS_INDEX_FOLLOW = 'index, follow';
    public const ROBOTS_NOINDEX_FOLLOW = 'noindex, follow';
    public const ROBOTS_INDEX_NOFOLLOW = 'index, nofollow';
    public const ROBOTS_NOINDEX_NOFOLLOW = 'noindex, nofollow';

    public static function getForPage(string $page, ?string $locale = null): ?self
    {
        $locale = $locale ?? app()->getLocale();
        
        return self::where('page', $page)
            ->where('locale', $locale)
            ->where('is_active', true)
            ->first();
    }

    public static function getGlobalSettings(): array
    {
        $locale = app()->getLocale();
        
        return [
            'meta_title' => Setting::getValue('seo_meta_title_' . $locale),
            'meta_description' => Setting::getValue('seo_meta_description_' . $locale),
            'meta_keywords' => Setting::getValue('seo_meta_keywords_' . $locale),
            'og_image' => Setting::getValue('seo_og_image'),
            'robots' => Setting::getValue('seo_robots', self::ROBOTS_INDEX_FOLLOW),
        ];
    }
}
