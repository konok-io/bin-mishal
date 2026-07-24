<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PostCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'slug',
        'description',
        'description_bn',
        'description_ar',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (PostCategory $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function (PostCategory $category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->name_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->name_ar ?: $value;
        }
        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
