<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\MorphTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'item_type',
        'item_id',
        'quantity',
        'unit_price',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    // Relationships
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    // Accessors
    public function getItemableTypeAttribute(): ?string
    {
        return $this->item_type;
    }

    public function getItemableIdAttribute(): ?int
    {
        return $this->item_id;
    }

    // Methods
    public static function createFromItem(Model $item, string $description, int $quantity = 1, ?float $unitPrice = null): self
    {
        $price = $unitPrice ?? (method_exists($item, 'total_amount') ? $item->total_amount : 0);

        return new self([
            'item_type' => get_class($item),
            'item_id' => $item->id,
            'description' => $description,
            'quantity' => $quantity,
            'unit_price' => $price,
            'total' => $quantity * $price,
        ]);
    }
}
