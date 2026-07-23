<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'user_id',
        'title',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'expiry_date',
        'is_verified',
        'verified_by',
        'verified_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'verified_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Methods
    public function verify(User $user): void
    {
        $this->update([
            'is_verified' => true,
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        return now()->diffInDays($this->expiry_date, false);
    }
}
