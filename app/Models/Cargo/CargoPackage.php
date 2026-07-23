<?php

namespace App\Models\Cargo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CargoPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'code',
        'length',
        'width',
        'height',
        'max_weight',
        'base_price',
        'vat_percentage',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'base_price' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class, 'cargo_package_id');
    }

    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->name_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->name_ar ?: $value;
        }
        return $value;
    }

    public function getDimensionsAttribute()
    {
        return "{$this->length} x {$this->width} x {$this->height} cm";
    }
}
