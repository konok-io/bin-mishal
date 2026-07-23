<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: string
{
    case CUSTOMER = 'customer';
    case EMPLOYEE = 'employee';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::CUSTOMER => __('Customer'),
            self::EMPLOYEE => __('Employee'),
            self::ADMIN => __('Admin'),
            self::SUPER_ADMIN => __('Super Admin'),
        };
    }
}
