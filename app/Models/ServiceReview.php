<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    use HasFactory;

    protected $table = 'service_reviews';

    protected $fillable = [
        'service_type', // 'umrah', 'visa', 'cargo', 'flight', 'investor'
        'service_id',
        'user_id',
        'customer_name',
        'customer_email',
        'rating',
        'title',
        'content',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeForService($query, string $type, int $id)
    {
        return $query->where('service_type', $type)->where('service_id', $id);
    }

    public function getAverageRatingAttribute()
    {
        return $this->rating;
    }

    public static function getAverageRating(string $type, int $id): float
    {
        return self::approved()
            ->where('service_type', $type)
            ->where('service_id', $id)
            ->avg('rating') ?? 0;
    }

    public static function getReviewCount(string $type, int $id): int
    {
        return self::approved()
            ->where('service_type', $type)
            ->where('service_id', $id)
            ->count();
    }
}
