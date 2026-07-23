<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Download extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'category',
        'icon',
        'color',
        'download_count',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'download_count' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    // Categories
    public const CATEGORY_BROCHURE = 'brochure';
    public const CATEGORY_PRICE_LIST = 'price_list';
    public const CATEGORY_FORM = 'form';
    public const CATEGORY_AGREEMENT = 'agreement';
    public const CATEGORY_GUIDE = 'guide';
    public const CATEGORY_OTHER = 'other';

    public const CATEGORIES = [
        self::CATEGORY_BROCHURE => 'Brochures',
        self::CATEGORY_PRICE_LIST => 'Price Lists',
        self::CATEGORY_FORM => 'Forms',
        self::CATEGORY_AGREEMENT => 'Agreements',
        self::CATEGORY_GUIDE => 'Guides',
        self::CATEGORY_OTHER => 'Other',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'desc')->orderBy('id', 'desc');
    }

    public function getTranslatedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->title[$locale] ?? $this->title['en'] ?? '';
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->description[$locale] ?? $this->description['en'] ?? null;
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }

        if ($this->getFirstMediaUrl('files')) {
            return $this->getFirstMediaUrl('files');
        }

        return null;
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    public function incrementDownload(): void
    {
        $this->increment('download_count');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')
            ->singleFile();
    }
}
