<?php

declare(strict_types=1);

namespace App\Models\CMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'created_by',
        'version_number',
        'snapshot',
        'change_summary',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    // Relationships
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get the next version number for a page
    public static function getNextVersionNumber(int $pageId): string
    {
        $latest = self::where('page_id', $pageId)
            ->orderByDesc('version_number')
            ->first();

        if (!$latest) {
            return '1.0';
        }

        $parts = explode('.', $latest->version_number);

        return $parts[0] . '.' . ((int) end($parts) + 1);
    }

    // Create a snapshot of current page state
    public static function createSnapshot(Page $page, User $user, ?string $summary = null): self
    {
        return self::create([
            'page_id' => $page->id,
            'created_by' => $user->id,
            'version_number' => self::getNextVersionNumber($page->id),
            'snapshot' => [
                'title' => $page->title,
                'slug' => $page->slug,
                'template' => $page->template,
                'hero_type' => $page->hero_type,
                'hero_title' => $page->hero_title,
                'hero_subtitle' => $page->hero_subtitle,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
                'status' => $page->status,
                'sections' => $page->sections->map(fn($s) => [
                    'id' => $s->id,
                    'section_type' => $s->section_type,
                    'name' => $s->name,
                    'content' => $s->content,
                    'settings' => $s->settings,
                    'data_source' => $s->data_source,
                    'visibility' => $s->visibility,
                    'order' => $s->order,
                    'status' => $s->status,
                    'items' => $s->items->map(fn($i) => [
                        'id' => $i->id,
                        'title' => $i->title,
                        'subtitle' => $i->subtitle,
                        'description' => $i->description,
                        'icon' => $i->icon,
                        'image' => $i->image,
                        'link_text' => $i->link_text,
                        'link_url' => $i->link_url,
                        'extra' => $i->extra,
                        'order' => $i->order,
                    ])->toArray(),
                ])->toArray(),
            ],
            'change_summary' => $summary,
        ]);
    }

    // Restore from this version
    public function restore(): void
    {
        $page = $this->page;
        $snapshot = $this->snapshot;

        // Restore basic page fields
        $page->title = $snapshot['title'];
        $page->slug = $snapshot['slug'];
        $page->template = $snapshot['template'];
        $page->hero_type = $snapshot['hero_type'];
        $page->hero_title = $snapshot['hero_title'];
        $page->hero_subtitle = $snapshot['hero_subtitle'];
        $page->meta_title = $snapshot['meta_title'];
        $page->meta_description = $snapshot['meta_description'];

        // Note: Sections restoration is complex and handled by PageVersionResource
    }
}
