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
}
