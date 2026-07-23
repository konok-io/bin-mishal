<?php

namespace App\Models\Cargo;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_number',
        'customer_id',
        'sender_name',
        'sender_phone',
        'sender_email',
        'sender_address',
        'sender_city',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'receiver_address',
        'receiver_city',
        'receiver_zone_id',
        'cargo_type_id',
        'cargo_package_id',
        'cargo_description',
        'quantity',
        'weight',
        'length',
        'width',
        'height',
        'declared_value',
        'shipping_cost',
        'vat_amount',
        'discount_amount',
        'coupon_id',
        'total_amount',
        'pickup_date',
        'pickup_time',
        'estimated_delivery',
        'delivery_days',
        'special_instructions',
        'status',
        'branch_id',
        'driver_id',
        'assigned_to',
        'payment_status',
        'payment_method',
        'payment_reference',
        'package_image',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'declared_value' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'pickup_date' => 'date',
        'estimated_delivery' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COLLECTED = 'collected';
    const STATUS_WAREHOUSE = 'warehouse';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_CUSTOMS = 'customs';
    const STATUS_BD_HUB = 'bd_hub';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';

    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_PAID = 'paid';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function cargoType(): BelongsTo
    {
        return $this->belongsTo(CargoType::class, 'cargo_type_id');
    }

    public function cargoPackage(): BelongsTo
    {
        return $this->belongsTo(CargoPackage::class, 'cargo_package_id');
    }

    public function receiverZone(): BelongsTo
    {
        return $this->belongsTo(CargoZone::class, 'receiver_zone_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }

    public function trackingHistory(): HasMany
    {
        return $this->hasMany(CargoTracking::class, 'cargo_id')->orderBy('timestamp', 'desc');
    }

    public function latestTracking()
    {
        return $this->hasOne(CargoTracking::class, 'cargo_id')->latestOfMany('timestamp');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => __('cargo.status.pending'),
            'confirmed' => __('cargo.status.confirmed'),
            'collected' => __('cargo.status.collected'),
            'warehouse' => __('cargo.status.warehouse'),
            'in_transit' => __('cargo.status.in_transit'),
            'customs' => __('cargo.status.customs'),
            'bd_hub' => __('cargo.status.bd_hub'),
            'out_for_delivery' => __('cargo.status.out_for_delivery'),
            'delivered' => __('cargo.status.delivered'),
            'cancelled' => __('cargo.status.cancelled'),
            'returned' => __('cargo.status.returned'),
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('status', [
            self::STATUS_CONFIRMED,
            self::STATUS_COLLECTED,
            self::STATUS_WAREHOUSE,
            self::STATUS_IN_TRANSIT,
            self::STATUS_CUSTOMS,
            self::STATUS_BD_HUB,
            self::STATUS_OUT_FOR_DELIVERY,
        ]);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeByTracking($query, $trackingNumber)
    {
        return $query->where('tracking_number', $trackingNumber);
    }
}
