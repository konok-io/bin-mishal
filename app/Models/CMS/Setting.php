<?php

declare(strict_types=1);

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
