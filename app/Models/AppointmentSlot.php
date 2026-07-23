<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'booked_count',
        'service_type',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'capacity' => 'integer',
            'booked_count' => 'integer',
        ];
    }

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
            ->whereColumn('booked_count', '<', 'capacity');
    }

    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    // Accessors
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_count);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->booked_count >= $this->capacity;
    }

    // Methods
    public function incrementBookedCount(): void
    {
        $this->increment('booked_count');

        if ($this->booked_count >= $this->capacity) {
            $this->update(['status' => 'full']);
        }
    }

    public function decrementBookedCount(): void
    {
        $this->decrement('booked_count');

        if ($this->booked_count < $this->capacity) {
            $this->update(['status' => 'available']);
        }
    }
}
