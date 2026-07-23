<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_request_id',
        'airline_id',
        'flight_no',
        'departure_datetime',
        'arrival_datetime',
        'stops',
        'layover_details',
        'baggage_allowance',
        'base_fare',
        'tax',
        'service_charge',
        'total_fare',
        'currency',
        'valid_until',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_datetime' => 'datetime',
            'arrival_datetime' => 'datetime',
            'valid_until' => 'date',
            'layover_details' => 'array',
            'base_fare' => 'decimal:2',
            'tax' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'total_fare' => 'decimal:2',
            'stops' => 'integer',
        ];
    }

    // Relationships
    public function flightRequest(): BelongsTo
    {
        return $this->belongsTo(FlightRequest::class);
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    // Scopes
    public function scopeValid($query)
    {
        return $query->where('status', 'sent')
            ->where('valid_until', '>=', now()->toDateString());
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    // Methods
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function accept(): void
    {
        $this->update(['status' => 'accepted']);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }
}
