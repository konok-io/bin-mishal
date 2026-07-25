<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppearanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'description',
        'section',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public const SECTIONS = [
        'colors' => 'Colors',
        'typography' => 'Typography',
        'layout' => 'Layout',
        'buttons' => 'Buttons',
        'cards' => 'Cards',
        'header' => 'Header',
        'footer' => 'Footer',
        'custom_css' => 'Custom CSS',
        'custom_js' => 'Custom JavaScript',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting?->value ?? $default;
    }

    public static function setValue(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value]
        );
    }
}
