<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => __('Unpaid'),
            self::PARTIAL => __('Partial'),
            self::PAID => __('Paid'),
            self::REFUNDED => __('Refunded'),
        };
    }
}
