<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class CookieConsent extends Component
{
    public function render()
    {
        return view('components.cookie-consent');
    }
}
