<?php
declare(strict_types=1);
namespace App\Enums;
enum VisaStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case DOCUMENT_PENDING = 'document_pending';
    case UNDER_REVIEW = 'under_review';
    case GOVERNMENT_PROCESSING = 'government_processing';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case DELIVERED = 'delivered';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::SUBMITTED => __('Submitted'),
            self::DOCUMENT_PENDING => __('Document Pending'),
            self::UNDER_REVIEW => __('Under Review'),
            self::GOVERNMENT_PROCESSING => __('Government Processing'),
            self::APPROVED => __('Approved'),
            self::REJECTED => __('Rejected'),
            self::DELIVERED => __('Delivered'),
        };
    }
}
