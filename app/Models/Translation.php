<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value_bn',
        'value_en',
        'value_ar',
        'source',
        'status',
        'last_seen_in_code_at',
        'updated_by',
    ];

    protected $casts = [
        'last_seen_in_code_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_COMPLETE = 'complete';
    public const STATUS_MISSING_BN = 'missing_bn';
    public const STATUS_MISSING_EN = 'missing_en';
    public const STATUS_MISSING_AR = 'missing_ar';
    public const STATUS_NEEDS_REVIEW = 'needs_review';

    // Cache key
    private const CACHE_KEY_PREFIX = 'translations_';

    /**
     * Get the user who last updated this translation.
     */
    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the full key (group.key).
     */
    public function getFullKeyAttribute(): string
    {
        return "{$this->group}.{$this->key}";
    }

    /**
     * Get translation value for a specific locale.
     */
    public function getValueForLocale(string $locale): ?string
    {
        return match($locale) {
            'bn' => $this->value_bn,
            'en' => $this->value_en,
            'ar' => $this->value_ar,
            default => $this->value_en,
        };
    }

    /**
     * Set translation value for a specific locale.
     */
    public function setValueForLocale(string $locale, ?string $value): void
    {
        match($locale) {
            'bn' => $this->value_bn = $value,
            'en' => $this->value_en = $value,
            'ar' => $this->value_ar = $value,
            default => null,
        };
    }

    /**
     * Check if translation is complete for all locales.
     */
    public function isComplete(): bool
    {
        return !empty($this->value_bn) 
            && !empty($this->value_en) 
            && !empty($this->value_ar);
    }

    /**
     * Update status based on current values.
     */
    public function updateStatus(): void
    {
        $this->status = match(true) {
            $this->isComplete() => self::STATUS_COMPLETE,
            empty($this->value_bn) && empty($this->value_en) && empty($this->value_ar) => self::STATUS_NEEDS_REVIEW,
            empty($this->value_bn) => self::STATUS_MISSING_BN,
            empty($this->value_en) => self::STATUS_MISSING_EN,
            empty($this->value_ar) => self::STATUS_MISSING_AR,
            default => self::STATUS_COMPLETE,
        };
    }

    /**
     * Scope to find translations missing any language.
     */
    public function scopeIncomplete($query)
    {
        return $query->where('status', '!=', self::STATUS_COMPLETE);
    }

    /**
     * Scope to find translations by group.
     */
    public function scopeForGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to find recently added translations.
     */
    public function scopeRecentlyAdded($query, int $days = 7)
    {
        return $query->where('last_seen_in_code_at', '>=', now()->subDays($days));
    }

    /**
     * Get cached translations for a locale.
     */
    public static function getCachedForLocale(string $locale): array
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $locale;

        return cache()->remember($cacheKey, 3600, function () use ($locale) {
            return self::query()
                ->whereNotNull("value_{$locale}")
                ->get()
                ->mapWithKeys(fn($t) => [$t->full_key => $t->getValueForLocale($locale)])
                ->toArray();
        });
    }

    /**
     * Clear translation cache.
     */
    public static function clearCache(?string $locale = null): void
    {
        $locales = $locale ? [$locale] : ['bn', 'en', 'ar'];

        foreach ($locales as $loc) {
            cache()->forget(self::CACHE_KEY_PREFIX . $loc);
        }
    }

    /**
     * Find or create a translation by group and key.
     */
    public static function findOrCreateByKey(string $group, string $key): self
    {
        return self::firstOrCreate(
            ['group' => $group, 'key' => $key],
            ['source' => 'code', 'status' => self::STATUS_NEEDS_REVIEW]
        );
    }
}
