<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PublicFooter extends Component
{
    public function __construct(
        public array $col1 = [],
        public array $col2 = [],
        public array $col3 = [],
        public array $bottom = []
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.public.footer');
    }
}
