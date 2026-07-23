<?php

namespace App\Models\Cargo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get setting value
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value
     */
    public static function setValue($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get multiple settings as array
     */
    public static function getMultiple(array $keys)
    {
        $settings = self::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        $result = [];
        
        foreach ($keys as $key) {
            $result[$key] = $settings[$key] ?? null;
        }
        
        return $result;
    }

    /**
     * Get all cargo settings as array
     */
    public static function getAllSettings()
    {
        return self::pluck('value', 'key')->toArray();
    }
}
