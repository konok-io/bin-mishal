<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PublicHeader extends Component
{
    public function __construct(
        public array $menu = [],
        public array $mobileMenu = []
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.public.header');
    }
}
