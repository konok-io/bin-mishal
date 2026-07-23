<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class UmrahPackage extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'title_bn',
        'title_ar',
        'slug',
        'description',
        'duration_days',
        'duration_nights',
        'makkah_hotel',
        'makkah_hotel_stars',
        'makkah_distance_meters',
        'makkah_nights',
        'madinah_hotel',
        'madinah_hotel_stars',
        'madinah_distance_meters',
        'madinah_nights',
        'transport_type',
        'meal_plan',
        'inclusions',
        'exclusions',
        'itinerary',
        'price_quad',
        'price_triple',
        'price_double',
        'price_single',
        'child_price',
        'infant_price',
        'currency',
        'departure_dates',
        'available_seats',
        'booked_seats',
        'featured_image',
        'gallery',
        'is_featured',
        'status',
    ];

    public array $translatable = [
        'title', 'title_bn', 'title_ar', 'description',
        'makkah_hotel', 'madinah_hotel'
    ];

    protected $casts = [
        'inclusions' => 'array',
        'exclusions' => 'array',
        'itinerary' => 'array',
        'gallery' => 'array',
        'departure_dates' => 'array',
        'price_quad' => 'decimal:2',
        'price_triple' => 'decimal:2',
        'price_double' => 'decimal:2',
        'price_single' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessors
    public function getAvailableSeatsAttribute(): int
    {
        if (!$this->available_seats) {
            return 0;
        }

        return max(0, $this->available_seats - $this->booked_seats);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_seats && $this->booked_seats >= $this->available_seats;
    }

    public function getStartingPriceAttribute(): float
    {
        return min(
            array_filter([
                $this->price_quad,
                $this->price_triple,
                $this->price_double,
                $this->price_single,
            ]) ?: [0]
        );
    }

    // Methods
    public function incrementBookedSeats(int $count = 1): void
    {
        $this->increment('booked_seats', $count);
    }
}
