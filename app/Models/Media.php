<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'folder',
        'name',
        'original_name',
        'file_name',
        'mime_type',
        'file_type',
        'file_size',
        'width',
        'height',
        'alt',
        'title',
        'caption',
        'description',
        'tags',
        'is_active',
        'download_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'is_active' => 'boolean',
        'download_count' => 'integer',
        'last_downloaded_at' => 'datetime',
        'tags' => 'array',
    ];

    protected $hidden = [
        'file_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByFolder($query, string $folder)
    {
        return $query->where('folder', $folder);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('original_name', 'like', "%{$search}%")
              ->orWhere('alt', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_name);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->isImage()) {
            return Storage::url("thumbnails/{$this->file_name}");
        }
        return null;
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDimensionsAttribute(): ?string
    {
        if ($this->width && $this->height) {
            return "{$this->width} × {$this->height}";
        }
        return null;
    }

    public function isImage(): bool
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ]);
    }

    public function isVideo(): bool
    {
        return in_array($this->mime_type, [
            'video/mp4',
            'video/webm',
            'video/ogg',
        ]);
    }

    public function isDocument(): bool
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function incrementDownload(): void
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }

    public static function getFolders(): array
    {
        return [
            'general' => 'General',
            'hero' => 'Hero Banners',
            'gallery' => 'Gallery',
            'blog' => 'Blog Images',
            'careers' => 'Careers',
            'testimonials' => 'Testimonials',
            'team' => 'Team Members',
            'logos' => 'Logos',
            'documents' => 'Documents',
            'cargo' => 'Cargo',
            'other' => 'Other',
        ];
    }

    public static function getFileTypes(): array
    {
        return [
            'image' => 'Image',
            'video' => 'Video',
            'document' => 'Document',
            'other' => 'Other',
        ];
    }
}
