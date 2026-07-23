<?php

declare(strict_types=1);

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    // Cache key
    private const CACHE_KEY = 'cms_settings';

    // Groups
    public const GROUP_APPEARANCE = 'appearance';
    public const GROUP_GENERAL = 'general';
    public const GROUP_SEO = 'seo';
    public const GROUP_SOCIAL = 'social';
    public const GROUP_BOOKING = 'booking';
    public const GROUP_EMAIL = 'email';
    public const GROUP_INTEGRATIONS = 'integrations';
    public const GROUP_LABELS = 'labels';
    public const GROUP_PAYMENT = 'payment';
    public const GROUP_SHIPPING = 'shipping';

    /**
     * Get a setting value
     */
    public static function getValue(string $key, $default = null)
    {
        $settings = self::all()->pluck('value', 'key')->toArray();

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function setValue(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value]
        );

        cache()->forget(self::CACHE_KEY);
    }

    /**
     * Get all settings
     */
    public static function allSettings(): array
    {
        return cache()->remember(self::CACHE_KEY, 3600, function () {
            return self::query()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Save multiple settings at once
     */
    public static function saveMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::setValue($key, $value);
        }
    }

    /**
     * Get theme settings
     */
    public static function getThemeSettings(): array
    {
        return [
            'primary_color' => self::getValue('primary_color', '#059669'),
            'secondary_color' => self::getValue('secondary_color', '#047857'),
            'accent_color' => self::getValue('accent_color', '#f59e0b'),
            'success_color' => self::getValue('success_color', '#10b981'),
            'warning_color' => self::getValue('warning_color', '#f59e0b'),
            'danger_color' => self::getValue('danger_color', '#ef4444'),
            'logo_light' => self::getValue('logo_light'),
            'logo_dark' => self::getValue('logo_dark'),
            'logo_mobile' => self::getValue('logo_mobile'),
            'favicon' => self::getValue('favicon'),
            'header_style' => self::getValue('header_style', 'solid'),
            'header_sticky' => self::getValue('header_sticky', true),
            'footer_style' => self::getValue('footer_style', '4-column'),
            'button_style' => self::getValue('button_style', 'rounded'),
            'container_max_width' => self::getValue('container_max_width', 'max-w-7xl'),
            'back_to_top' => self::getValue('back_to_top', true),
            'preloader' => self::getValue('preloader', false),
            'whatsapp_float' => self::getValue('whatsapp_float', true),
            'whatsapp_message' => self::getValue('whatsapp_message', 'Hello!'),
            'dark_mode_toggle' => self::getValue('dark_mode_toggle', false),
        ];
    }

    /**
     * Default settings that can be seeded
     */
    public static function defaults(): array
    {
        return [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Bin Mishal Travels', 'type' => 'text', 'label' => 'Site Name'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Your Trusted Travel Partner', 'type' => 'text', 'label' => 'Site Tagline'],
            ['group' => 'general', 'key' => 'site_description', 'value' => '', 'type' => 'text', 'label' => 'Site Description'],
            ['group' => 'general', 'key' => 'contact_email', 'value' => 'info@binmishal.com', 'type' => 'text', 'label' => 'Contact Email'],
            ['group' => 'general', 'key' => 'contact_phone', 'value' => '+966 XX XXX XXXX', 'type' => 'text', 'label' => 'Contact Phone'],
            ['group' => 'general', 'key' => 'contact_whatsapp', 'value' => '+966 XX XXX XXXX', 'type' => 'text', 'label' => 'WhatsApp'],
            ['group' => 'general', 'key' => 'contact_address', 'value' => 'Saudi Arabia', 'type' => 'text', 'label' => 'Address'],
            ['group' => 'general', 'key' => 'working_hours', 'value' => 'Sat-Thu: 9AM-6PM', 'type' => 'text', 'label' => 'Working Hours'],
            
            // Appearance
            ['group' => 'appearance', 'key' => 'primary_color', 'value' => '#006C35', 'type' => 'text', 'label' => 'Primary Color'],
            ['group' => 'appearance', 'key' => 'secondary_color', 'value' => '#C8A951', 'type' => 'text', 'label' => 'Secondary Color'],
            ['group' => 'appearance', 'key' => 'accent_color', 'value' => '#1B3A5C', 'type' => 'text', 'label' => 'Accent Color'],
            ['group' => 'appearance', 'key' => 'logo_light', 'value' => '', 'type' => 'file', 'label' => 'Logo (Light)'],
            ['group' => 'appearance', 'key' => 'logo_dark', 'value' => '', 'type' => 'file', 'label' => 'Logo (Dark)'],
            ['group' => 'appearance', 'key' => 'favicon', 'value' => '', 'type' => 'file', 'label' => 'Favicon'],
            ['group' => 'appearance', 'key' => 'header_sticky', 'value' => '1', 'type' => 'boolean', 'label' => 'Sticky Header'],
            ['group' => 'appearance', 'key' => 'whatsapp_float', 'value' => '1', 'type' => 'boolean', 'label' => 'WhatsApp Float Button'],
            ['group' => 'appearance', 'key' => 'back_to_top', 'value' => '1', 'type' => 'boolean', 'label' => 'Back to Top Button'],
            
            // SEO
            ['group' => 'seo', 'key' => 'meta_title', 'value' => '', 'type' => 'text', 'label' => 'Default Meta Title'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => '', 'type' => 'text', 'label' => 'Default Meta Description'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => '', 'type' => 'text', 'label' => 'Meta Keywords'],
            ['group' => 'seo', 'key' => 'og_image', 'value' => '', 'type' => 'file', 'label' => 'OG Image'],
            
            // Booking
            ['group' => 'booking', 'key' => 'booking_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Enable Online Booking'],
            ['group' => 'booking', 'key' => 'booking_confirmation_required', 'value' => '1', 'type' => 'boolean', 'label' => 'Require Confirmation'],
            ['group' => 'booking', 'key' => 'max_passengers', 'value' => '9', 'type' => 'number', 'label' => 'Max Passengers per Booking'],
            
            // Currency
            ['group' => 'general', 'key' => 'currency_primary', 'value' => 'SAR', 'type' => 'text', 'label' => 'Primary Currency (KSA)'],
            ['group' => 'general', 'key' => 'currency_secondary', 'value' => 'BDT', 'type' => 'text', 'label' => 'Secondary Currency (BD)'],
            ['group' => 'general', 'key' => 'exchange_rate', 'value' => '1', 'type' => 'number', 'label' => 'SAR to BDT Rate'],
        ];
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        // Clear cache when settings are modified
        static::updated(fn(Setting $setting) => self::clearCache());
        static::created(fn(Setting $setting) => self::clearCache());
        static::deleted(fn(Setting $setting) => self::clearCache());
    }
}
