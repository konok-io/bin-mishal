<?php

declare(strict_types=1);

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case ISSUED = 'issued';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
}
