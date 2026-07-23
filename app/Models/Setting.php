<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    // Cache key
    private const CACHE_KEY = 'settings';

    // Default settings
    public static function defaults(): array
    {
        return [
            'app_name' => 'Bin Mishal Travel',
            'app_name_bn' => 'বিন মিশাল ট্রাভেল',
            'app_name_ar' => 'بن ميثال للسفر',
            'app_logo' => null,
            'app_favicon' => null,
            'company_phone' => '+966 XX XXX XXXX',
            'company_email' => 'info@binmishal.com',
            'company_address' => 'Saudi Arabia',
            'tax_rate' => '15',
            'currency' => 'SAR',
            'date_format' => 'd M Y',
            'time_format' => 'H:i',
            'timezone' => 'Asia/Riyadh',
            'main_branch_id' => null,
            'whatsapp_number' => '+966 XX XXX XXXX',
            'facebook_url' => null,
            'instagram_url' => null,
            'twitter_url' => null,
        ];
    }

    // Get setting value
    public static function getValue(string $key, $default = null)
    {
        $settings = self::all()->pluck('value', 'key')->toArray();
        $defaults = self::defaults();

        $value = $settings[$key] ?? $defaults[$key] ?? $default;

        // Auto-cast based on type
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $value = self::castValue($value, $setting->type);
        }

        return $value;
    }

    // Set setting value
    public static function setValue(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value]
        );

        cache()->forget(self::CACHE_KEY);
    }

    // Get all settings as array
    public static function allSettings(): array
    {
        return cache()->remember(self::CACHE_KEY, 3600, function () {
            $settings = self::all()->pluck('value', 'key')->toArray();
            return array_merge(self::defaults(), $settings);
        });
    }

    // Cast value based on type
    private static function castValue($value, string $type)
    {
        return match ($type) {
            'number' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
