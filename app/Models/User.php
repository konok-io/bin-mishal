<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Language;
use App\Enums\UserStatus;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, HasRoles, InteractsWithMedia, LogsActivity, Notifiable, SoftDeletes;

    protected static $logName = 'user';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'email',
        'phone',
        'whatsapp',
        'password',
        'user_type',
        'nationality',
        'passport_no',
        'iqama_no',
        'iqama_expiry',
        'city',
        'address',
        'preferred_language',
        'avatar',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'otp_code',
        'otp_expires_at',
        'referred_by',
        'referral_code',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'passport_no',
        'iqama_no',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'iqama_expiry' => 'date',
            'last_login_at' => 'datetime',
            'user_type' => UserType::class,
            'status' => UserStatus::class,
            'preferred_language' => Language::class,
            'password' => 'hashed',
        ];
    }

    /**
     * Encrypt PII fields on save
     */
    public function setPassportNoAttribute(?string $value): void
    {
        $this->attributes['passport_no'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt PII fields on access
     */
    public function getPassportNoAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Return as-is if not encrypted (legacy data)
        }
    }

    public function setIqamaNoAttribute(?string $value): void
    {
        $this->attributes['iqama_no'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getIqamaNoAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    // Relationships
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notifiable_id')
            ->where('notifiable_type', self::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function createdBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'issued_by');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name_bn ?? $this->name;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('avatar');
    }

    // Scopes
    public function scopeCustomers($query)
    {
        return $query->where('user_type', UserType::CUSTOMER);
    }

    public function scopeEmployees($query)
    {
        return $query->where('user_type', UserType::EMPLOYEE);
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('user_type', [UserType::ADMIN, UserType::SUPER_ADMIN]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::ACTIVE);
    }

    // Methods
    public function isCustomer(): bool
    {
        return $this->user_type === UserType::CUSTOMER;
    }

    public function isEmployee(): bool
    {
        return $this->user_type === UserType::EMPLOYEE;
    }

    public function isAdmin(): bool
    {
        return in_array($this->user_type, [UserType::ADMIN, UserType::SUPER_ADMIN]);
    }

    public function isSuperAdmin(): bool
    {
        return $this->user_type === UserType::SUPER_ADMIN;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    public function generateReferralCode(): string
    {
        $code = strtoupper(substr($this->name, 0, 3)) . random_int(100, 999);
        $this->update(['referral_code' => $code]);
        return $code;
    }

    public function verifyOtp(string $otp): bool
    {
        if ($this->otp_code !== $otp) {
            return false;
        }

        if ($this->otp_expires_at && $this->otp_expires_at->isPast()) {
            return false;
        }

        $this->update(['otp_code' => null, 'otp_expires_at' => null]);
        return true;
    }
}
