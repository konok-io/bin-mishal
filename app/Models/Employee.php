<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'designation',
        'department',
        'joining_date',
        'salary',
        'iqama_no',
        'iqama_expiry',
        'passport_no',
        'emergency_contact',
        'bank_account',
        'status',
        'biometric_id',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'iqama_expiry' => 'date',
            'salary' => 'decimal:2',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(BiometricAttendance::class, 'employee_id');
    }

    // Accessors
    public function getNameAttribute(): string
    {
        return $this->user?->name ?? 'N/A';
    }

    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->user?->phone;
    }

    // Methods
    public static function generateCode(): string
    {
        return 'EMP-' . date('Y') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
