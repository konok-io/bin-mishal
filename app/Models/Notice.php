<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'type',
        'link_url',
        'link_text',
        'priority',
        'start_date',
        'end_date',
        'visibility',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'link_text' => 'array',
        'visibility' => 'array',
        'priority' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public const TYPE_INFO = 'info';
    public const TYPE_WARNING = 'warning';
    public const TYPE_URGENT = 'urgent';
    public const TYPE_SUCCESS = 'success';

    public const TYPES = [
        self::TYPE_INFO => 'Information',
        self::TYPE_WARNING => 'Warning',
        self::TYPE_URGENT => 'Urgent',
        self::TYPE_SUCCESS => 'Success',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $now = now();

        return $query
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now);
            });
    }

    public function scopeForLocale($query, string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $query->where(function ($q) use ($locale) {
            $q->whereNull('visibility')
              ->orWhereJsonContains('visibility', $locale);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('id', 'desc');
    }

    public function getTranslatedContentAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->content[$locale] ?? $this->content['en'] ?? '';
    }

    public function getTranslatedLinkTextAttribute(): ?string
    {
        if (!$this->link_text) {
            return null;
        }

        $locale = app()->getLocale();
        return $this->link_text[$locale] ?? $this->link_text['en'] ?? null;
    }

    public function isCurrentlyVisible(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Get all currently active notices for display
     */
    public static function getActiveNotices(): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = 'active_notices_' . app()->getLocale();

        return Cache::remember($cacheKey, 300, function () {
            return self::active()
                ->current()
                ->forLocale()
                ->ordered()
                ->get();
        });
    }

    public static function clearCache(): void
    {
        foreach (['bn', 'en', 'ar'] as $locale) {
            Cache::forget("active_notices_{$locale}");
        }
    }

    protected static function booted(): void
    {
        static::updated(fn(Notice $notice) => self::clearCache());
        static::deleted(fn(Notice $notice) => self::clearCache());
        static::created(fn(Notice $notice) => self::clearCache());
    }
}
