<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Airline extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'iata_code',
        'icao_code',
        'logo',
        'country',
        'status',
    ];

    public array $translatable = ['name', 'name_ar', 'name_bn'];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
