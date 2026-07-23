<?php

namespace App\Models\Cargo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CargoType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'icon',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class, 'cargo_type_id');
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
}
