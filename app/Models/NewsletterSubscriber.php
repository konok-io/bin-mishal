<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'is_active',
        'is_verified',
        'verification_token',
        'unsubscribe_token',
        'subscribed_at',
        'unsubscribed_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected $hidden = [
        'verification_token',
        'unsubscribe_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (NewsletterSubscriber $subscriber) {
            if (empty($subscriber->verification_token)) {
                $subscriber->verification_token = Str::random(64);
            }
            if (empty($subscriber->unsubscribe_token)) {
                $subscriber->unsubscribe_token = Str::random(64);
            }
            if (empty($subscriber->subscribed_at)) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_verified', true);
    }

    public function verify(): void
    {
        $this->update(['is_verified' => true]);
    }

    public function unsubscribe(): void
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    public static function isSubscribed(string $email): bool
    {
        return static::where('email', strtolower($email))
            ->where('is_active', true)
            ->where('is_verified', true)
            ->exists();
    }

    public static function subscribe(string $email, ?string $name = null): self
    {
        $subscriber = static::firstOrCreate(
            ['email' => strtolower($email)],
            [
                'name' => $name,
                'is_active' => true,
                'is_verified' => false,
            ]
        );

        // Generate new verification token if needed
        if (!$subscriber->is_verified) {
            $subscriber->update([
                'verification_token' => Str::random(64),
                'subscribed_at' => now(),
            ]);
        }

        return $subscriber;
    }
}
