<?php

declare(strict_types=1);

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoRedirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_path',
        'new_path',
        'type',
        'is_active',
        'hit_count',
        'description',
        'last_hit_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hit_count' => 'integer',
        'last_hit_at' => 'datetime',
    ];

    // Redirect types
    public const TYPE_301 = '301';
    public const TYPE_302 = '302';
    public const TYPE_307 = '307';
    public const TYPE_308 = '308';

    public const TYPES = [
        self::TYPE_301 => '301 Moved Permanently',
        self::TYPE_302 => '302 Found (Temporary)',
        self::TYPE_307 => '307 Temporary Redirect',
        self::TYPE_308 => '308 Permanent Redirect',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Check if a path should be redirected
    public static function shouldRedirect(string $path): ?self
    {
        // Normalize path
        $path = '/' . ltrim($path, '/');

        return self::active()
            ->where('old_path', $path)
            ->first();
    }

    // Record a hit
    public function recordHit(): void
    {
        $this->increment('hit_count');
        $this->update(['last_hit_at' => now()]);
    }

    // Get redirect code
    public function getCode(): int
    {
        return (int) $this->type;
    }
}
