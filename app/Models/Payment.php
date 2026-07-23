<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_no',
        'customer_id',
        'invoice_id',
        'booking_id',
        'amount',
        'currency',
        'exchange_rate',
        'method',
        'transaction_id',
        'gateway_response',
        'status',
        'receipt_file',
        'notes',
        'created_by',
        'verified_by',
        'paid_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'exchange_rate' => 'decimal:4',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public static function generateNo(): string
    {
        return 'PAY-' . date('ymd') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update related invoice
        if ($this->invoice) {
            $this->invoice->addPayment($this->amount);
        }

        // Update related booking
        if ($this->booking) {
            $this->booking->addPayment($this->amount);
        }
    }

    public function verify(User $user): void
    {
        $this->update([
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);

        $this->complete();
    }

    public function fail(): void
    {
        $this->update(['status' => 'failed']);
    }

    public function refund(): void
    {
        $this->update(['status' => 'refunded']);
    }
}
