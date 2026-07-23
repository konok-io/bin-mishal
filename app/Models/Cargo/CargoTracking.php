<?php

namespace App\Models\Cargo;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'cargo_id',
        'status',
        'status_bn',
        'status_ar',
        'description',
        'description_bn',
        'description_ar',
        'location',
        'branch_id',
        'updated_by',
        'timestamp',
        'notify_customer',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'notify_customer' => 'boolean',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->status_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->status_ar ?: $value;
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->description_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->description_ar ?: $value;
        }
        return $value;
    }
}
