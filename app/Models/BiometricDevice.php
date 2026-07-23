<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiometricDevice extends Model
{
    use HasFactory;

    protected $table = 'biometric_devices';

    protected $fillable = [
        'branch_id',
        'device_id',
        'name',
        'brand',
        'model',
        'ip_address',
        'port',
        'comm_key',
        'sync_method',
        'webhook_url',
        'api_key',
        'status',
        'last_sync_at',
        'sync_interval',
        'notes',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
        'port' => 'integer',
        'sync_interval' => 'integer',
    ];

    // Supported device brands
    public const BRANDS = [
        'zkteco' => 'ZKTeco',
        'hikvision' => 'Hikvision',
        'essl' => 'eSSL',
        'realtime' => 'Realtime',
        'suprema' => 'Suprema',
        'fingertec' => 'FingerTec',
        'digital' => 'Digital Persona',
        'other' => 'Other',
    ];

    // Sync methods
    public const SYNC_METHODS = [
        'webhook' => 'Webhook (Real-time)',
        'polling' => 'Polling (Scheduled)',
        'manual' => 'Manual Sync',
        'csv' => 'CSV Import',
    ];

    // Status options
    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'maintenance' => 'Maintenance',
        'offline' => 'Offline',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(BiometricAttendance::class, 'device_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOnline($query)
    {
        return $query->where('status', 'active')
            ->whereNotNull('ip_address');
    }

    public function isOnline(): bool
    {
        return $this->status === 'active' && $this->ip_address !== null;
    }

    public function needsSync(): bool
    {
        if (!$this->last_sync_at) {
            return true;
        }
        
        return $this->last_sync_at->addMinutes($this->sync_interval)->isPast();
    }

    public function markSynced(): void
    {
        $this->update(['last_sync_at' => now()]);
    }

    public function markOffline(): void
    {
        $this->update(['status' => 'offline']);
    }

    public function getConnectionString(): string
    {
        return "tcp://{$this->ip_address}:{$this->port}";
    }
}
