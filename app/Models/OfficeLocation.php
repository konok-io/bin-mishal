<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'whatsapp',
        'latitude',
        'longitude',
        'working_hours',
        'is_headquarters',
        'is_active',
        'map_zoom',
        'map_type',
        'description',
        'image',
        'sort_order',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'map_zoom' => 'integer',
        'is_headquarters' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHeadquarters($query)
    {
        return $query->where('is_headquarters', true);
    }

    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->country,
        ]);
        
        return implode(', ', $parts);
    }

    public function getGoogleMapsUrlAttribute(): string
    {
        $query = urlencode($this->formatted_address);
        return "https://www.google.com/maps/search/?api=1&query={$query}";
    }

    public static function getPrimaryLocation(): ?self
    {
        return static::active()
            ->orderByDesc('is_headquarters')
            ->orderBy('sort_order')
            ->first();
    }
}
