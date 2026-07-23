<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_code',
        'company_name',
        'sponsor_name',
        'sponsor_id_no',
        'profession',
        'work_city',
        'monthly_income',
        'source',
        'assigned_to',
        'lifetime_value',
        'total_bookings',
        'notes',
        'tags',
    ];

    protected $hidden = [
        'sponsor_id_no',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'monthly_income' => 'decimal:2',
            'lifetime_value' => 'decimal:2',
            'total_bookings' => 'integer',
        ];
    }

    /**
     * Encrypt sponsor ID on save
     */
    public function setSponsorIdNoAttribute(?string $value): void
    {
        $this->attributes['sponsor_id_no'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt sponsor ID on access
     */
    public function getSponsorIdNoAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Legacy data
        }
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function visaApplications(): HasMany
    {
        return $this->hasMany(VisaApplication::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'converted_customer_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class);
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
        return 'CUST-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public function incrementBookings(): void
    {
        $this->increment('total_bookings');
    }

    public function updateLifetimeValue(): void
    {
        $total = $this->bookings()->sum('total_amount');
        $this->update(['lifetime_value' => $total]);
    }
}
