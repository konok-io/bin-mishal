<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'name',
        'icon',
        'url',
        'color',
        'order',
        'is_visible',
    ];

    protected $casts = [
        'name' => 'array',
        'is_visible' => 'boolean',
        'order' => 'integer',
    ];

    // Available platforms with default settings
    public const PLATFORMS = [
        'facebook' => [
            'icon' => 'fab fa-facebook-f',
            'color' => '#1877F2',
            'name' => 'Facebook',
        ],
        'twitter' => [
            'icon' => 'fab fa-x-twitter',
            'color' => '#000000',
            'name' => 'X (Twitter)',
        ],
        'instagram' => [
            'icon' => 'fab fa-instagram',
            'color' => '#E4405F',
            'name' => 'Instagram',
        ],
        'youtube' => [
            'icon' => 'fab fa-youtube',
            'color' => '#FF0000',
            'name' => 'YouTube',
        ],
        'linkedin' => [
            'icon' => 'fab fa-linkedin-in',
            'color' => '#0A66C2',
            'name' => 'LinkedIn',
        ],
        'tiktok' => [
            'icon' => 'fab fa-tiktok',
            'color' => '#000000',
            'name' => 'TikTok',
        ],
        'snapchat' => [
            'icon' => 'fab fa-snapchat',
            'color' => '#FFFC00',
            'name' => 'Snapchat',
        ],
        'whatsapp' => [
            'icon' => 'fab fa-whatsapp',
            'color' => '#25D366',
            'name' => 'WhatsApp',
        ],
        'telegram' => [
            'icon' => 'fab fa-telegram',
            'color' => '#0088cc',
            'name' => 'Telegram',
        ],
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getTranslatedNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['en'] ?? '';
    }
}
