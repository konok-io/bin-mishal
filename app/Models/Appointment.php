<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_no',
        'customer_id',
        'appointment_slot_id',
        'branch_id',
        'service_type',
        'customer_name',
        'customer_phone',
        'customer_email',
        'preferred_date',
        'preferred_time',
        'purpose',
        'status',
        'notes',
        'cancellation_reason',
        'assigned_to',
        'reminder_sent_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'preferred_time' => 'datetime:H:i',
            'reminder_sent_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(AppointmentSlot::class, 'appointment_slot_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('preferred_date', '>=', today())
            ->where('status', 'scheduled');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('preferred_date', today());
    }

    // Methods
    public static function generateNo(): string
    {
        return 'APT-' . date('ymd') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel(string $reason): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);
    }

    public function markNoShow(): void
    {
        $this->update(['status' => 'no_show']);
    }
}
