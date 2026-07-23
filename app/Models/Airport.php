<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Airport extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'city',
        'city_bn',
        'country',
        'iata_code',
        'timezone',
        'status',
    ];

    public array $translatable = ['city', 'city_bn'];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getDisplayNameAttribute(): string
    {
        return "{$this->city} ({$this->iata_code})";
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
