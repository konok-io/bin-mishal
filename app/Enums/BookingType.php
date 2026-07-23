<?php

declare(strict_types=1);

namespace App\Enums;

enum BookingType: string
{
    case TICKET = 'ticket';
    case UMRAH = 'umrah';
    case VISA = 'visa';
    case PACKAGE = 'package';

    public function label(): string
    {
        return match ($this) {
            self::TICKET => __('Air Ticket'),
            self::UMRAH => __('Umrah'),
            self::VISA => __('Visa'),
            self::PACKAGE => __('Package'),
        };
    }
}
