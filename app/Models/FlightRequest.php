<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_no',
        'customer_id',
        'trip_type',
        'from_airport_id',
        'to_airport_id',
        'departure_date',
        'return_date',
        'adults',
        'children',
        'infants',
        'cabin_class',
        'preferred_airline_id',
        'budget_min',
        'budget_max',
        'baggage_requirement',
        'special_request',
        'status',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'adults' => 'integer',
            'children' => 'integer',
            'infants' => 'integer',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function fromAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'from_airport_id');
    }

    public function toAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'to_airport_id');
    }

    public function preferredAirline(): BelongsTo
    {
        return $this->belongsTo(Airline::class, 'preferred_airline_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(FlightQuote::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Methods
    public static function generateNo(): string
    {
        return 'FR-' . date('ymd') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function getTotalPassengersAttribute(): int
    {
        return $this->adults + $this->children + $this->infants;
    }
}
