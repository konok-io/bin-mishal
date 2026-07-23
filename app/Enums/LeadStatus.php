<?php

declare(strict_types=1);

namespace App\Enums;

enum LeadStatus: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case QUALIFIED = 'qualified';
    case CONVERTED = 'converted';
    case LOST = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::NEW => __('New'),
            self::CONTACTED => __('Contacted'),
            self::QUALIFIED => __('Qualified'),
            self::CONVERTED => __('Converted'),
            self::LOST => __('Lost'),
        };
    }
}
