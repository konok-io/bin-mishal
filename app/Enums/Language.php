<?php

declare(strict_types=1);

namespace App\Enums;

enum Language: string
{
    case BN = 'bn';
    case EN = 'en';
    case AR = 'ar';

    public function label(): string
    {
        return match ($this) {
            self::BN => __('Bengali'),
            self::EN => __('English'),
            self::AR => __('Arabic'),
        };
    }

    public function direction(): string
    {
        return match ($this) {
            self::AR => 'rtl',
            default => 'ltr',
        };
    }
}
