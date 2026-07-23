<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Services\CMS\MenuBuilder;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PublicMenu extends Component
{
    public function __construct(
        public string $location,
        protected MenuBuilder $menuBuilder
    ) {}

    public function render(): View|Closure|string
    {
        $items = $this->menuBuilder->renderMenu($this->location);

        return view('components.public.menu', [
            'items' => $items,
            'location' => $this->location,
        ]);
    }
}
