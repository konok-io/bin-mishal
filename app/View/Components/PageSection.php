<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\CMS\PageSection;
use App\Services\CMS\SectionDataResolver;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageSectionComponent extends Component
{
    public function __construct(
        public PageSection $section,
        protected SectionDataResolver $dataResolver
    ) {}

    public function render(): View|Closure|string
    {
        $type = $this->section->section_type;
        $viewPath = "public.sections.{$type}";

        if (!view()->exists($viewPath)) {
            $viewPath = 'public.sections.generic';
        }

        $content = $this->section->getResolvedContent();
        $settings = $this->section->getResolvedSettings();
        $items = $this->section->items;

        // Resolve dynamic data if no static items
        $dynamicData = null;
        if ($this->section->data_source && $items->isEmpty()) {
            $dynamicData = $this->dataResolver->resolve($this->section);
        }

        return view($viewPath, [
            'section' => $this->section,
            'content' => $content,
            'settings' => $settings,
            'items' => $items,
            'dynamicData' => $dynamicData,
        ]);
    }
}
