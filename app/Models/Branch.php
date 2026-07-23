<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Branch extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'code',
        'city',
        'address',
        'phone',
        'email',
        'whatsapp',
        'latitude',
        'longitude',
        'is_main',
        'status',
    ];

    public array $translatable = ['name', 'name_ar', 'address'];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_main' => 'boolean',
        ];
    }

    // Relationships
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointmentSlots(): HasMany
    {
        return $this->hasMany(AppointmentSlot::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    // Methods
    public static function getMainBranch(): ?self
    {
        return self::main()->active()->first();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
