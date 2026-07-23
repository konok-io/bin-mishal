<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected static $logName = 'booking';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['booking_no', 'status', 'total_amount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'booking_no',
        'pnr',
        'customer_id',
        'flight_quote_id',
        'booking_type',
        'passenger_count',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'booking_status',
        'issued_by',
        'issue_date',
        'ticket_file',
        'cancellation_reason',
        'refund_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'issue_date' => 'date',
        'booking_type' => BookingType::class,
        'booking_status' => BookingStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function flightQuote(): BelongsTo
    {
        return $this->belongsTo(FlightQuote::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('booking_status', BookingStatus::PENDING);
    }

    public function scopeIssued($query)
    {
        return $query->where('booking_status', BookingStatus::ISSUED);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', PaymentStatus::UNPAID);
    }

    // Methods
    public static function generateNo(): string
    {
        return 'BK-' . date('ymd') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function issue(User $user): void
    {
        $this->update([
            'booking_status' => BookingStatus::ISSUED,
            'issued_by' => $user->id,
            'issue_date' => now(),
        ]);
    }

    public function cancel(string $reason): void
    {
        $this->update([
            'booking_status' => BookingStatus::CANCELLED,
            'cancellation_reason' => $reason,
        ]);
    }

    public function updatePaymentStatus(): void
    {
        $paid = (float) $this->paid_amount;
        $total = (float) $this->total_amount;

        if ($paid >= $total) {
            $this->update(['payment_status' => PaymentStatus::PAID]);
        } elseif ($paid > 0) {
            $this->update(['payment_status' => PaymentStatus::PARTIAL]);
        } else {
            $this->update(['payment_status' => PaymentStatus::UNPAID]);
        }
    }

    public function addPayment(float $amount): void
    {
        $this->increment('paid_amount', $amount);
        $this->update(['due_amount' => $this->total_amount - $this->paid_amount]);
        $this->updatePaymentStatus();
    }
}
