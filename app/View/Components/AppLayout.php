<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public function __construct(
        public ?array $seo = null,
        public bool $showHeader = true,
        public bool $showFooter = true
    ) {}

    public function render(): View|Closure|string
    {
        return view('layouts.cms');
    }
}
