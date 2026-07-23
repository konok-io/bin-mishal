<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'title_bn',
        'title_ar',
        'slug',
        'excerpt',
        'excerpt_bn',
        'excerpt_ar',
        'content',
        'content_bn',
        'content_ar',
        'featured_image',
        'author_id',
        'category_id',
        'is_featured',
        'is_published',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'view_count',
        'reading_time',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'reading_time' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PostTag::class, 'post_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function getTitleAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->title_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->title_ar ?: $value;
        }
        return $value;
    }

    public function getExcerptAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->excerpt_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->excerpt_ar ?: $value;
        }
        return $value;
    }

    public function getContentAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->content_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->content_ar ?: $value;
        }
        return $value;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }
}
