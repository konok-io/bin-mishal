<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'title',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'nationality',
        'passport_no',
        'passport_expiry',
        'passport_issue_country',
        'passport_scan',
        'visa_scan',
        'seat_preference',
        'meal_preference',
        'passenger_type',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'passport_expiry' => 'date',
        ];
    }

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return trim("{$this->title} {$this->first_name} {$this->last_name}");
    }

    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    public function isPassportExpired(): bool
    {
        return $this->passport_expiry && $this->passport_expiry->isPast();
    }

    public function getPassportDaysRemainingAttribute(): ?int
    {
        if (!$this->passport_expiry) {
            return null;
        }

        return now()->diffInDays($this->passport_expiry, false);
    }
}
