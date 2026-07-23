<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\CMS\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PublicBreadcrumb extends Component
{
    public function __construct(
        public Page $page,
        public string $locale
    ) {}

    public function render(): View|Closure|string
    {
        $crumbs = $this->buildCrumbs();

        return view('components.public.breadcrumb', [
            'crumbs' => $crumbs,
        ]);
    }

    protected function buildCrumbs(): array
    {
        $crumbs = [
            ['label' => __('navigation.home'), 'url' => "/{$this->locale}"],
        ];

        $parent = $this->page->parent;

        while ($parent) {
            array_unshift($crumbs, [
                'label' => $parent->title[$this->locale] ?? $parent->title['en'] ?? '',
                'url' => $parent->getUrl($this->locale),
            ]);

            $parent = $parent->parent;
        }

        $crumbs[] = [
            'label' => $this->page->title[$this->locale] ?? $this->page->title['en'] ?? '',
            'url' => null,
        ];

        return $crumbs;
    }
}
