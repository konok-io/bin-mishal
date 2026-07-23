<?php

declare(strict_types=1);

namespace App\Enums;

enum InvestorApplicationStatus: string
{
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::SUBMITTED => 'Submitted',
            self::UNDER_REVIEW => 'Under Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SUBMITTED => 'gray',
            self::UNDER_REVIEW => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::CANCELLED => 'gray',
        };
    }

    public function canTransitionTo(self $newStatus): bool
    {
        return match ($this) {
            self::SUBMITTED => in_array($newStatus, [self::UNDER_REVIEW, self::CANCELLED]),
            self::UNDER_REVIEW => in_array($newStatus, [self::APPROVED, self::REJECTED, self::CANCELLED]),
            self::APPROVED, self::REJECTED, self::CANCELLED => false,
        };
    }
}
