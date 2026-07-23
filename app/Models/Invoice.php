<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'title',
        'description',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total',
        'paid_amount',
        'balance',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'balance' => 'decimal:2',
            'issue_date' => 'date',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->where('due_date', '<', now());
    }

    // Methods
    public static function generateNo(): string
    {
        return 'INV-' . date('ymd') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('total');
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->balance = $this->total - $this->paid_amount;
        $this->save();
    }

    public function addPayment(float $amount): void
    {
        $this->paid_amount += $amount;
        $this->balance = $this->total - $this->paid_amount;

        if ($this->balance <= 0) {
            $this->status = 'paid';
            $this->paid_date = now();
        } else {
            $this->status = 'partial';
        }

        $this->save();
    }

    public function send(): void
    {
        $this->update(['status' => 'sent']);
    }

    public function markAsOverdue(): void
    {
        if ($this->status !== 'paid') {
            $this->update(['status' => 'overdue']);
        }
    }
}
