<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'event',
        'subject',
        'subject_bn',
        'subject_ar',
        'body',
        'body_bn',
        'body_ar',
        'variables',
        'channels',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'channels' => 'array',
        'is_active' => 'boolean',
    ];

    public const TYPE_EMAIL = 'email';
    public const TYPE_SMS = 'sms';
    public const TYPE_WHATSAPP = 'whatsapp';
    public const TYPE_PUSH = 'push';

    public const TYPES = [
        self::TYPE_EMAIL => 'Email',
        self::TYPE_SMS => 'SMS',
        self::TYPE_WHATSAPP => 'WhatsApp',
        self::TYPE_PUSH => 'Push Notification',
    ];

    public const EVENT_BOOKING_CREATED = 'booking_created';
    public const EVENT_BOOKING_CONFIRMED = 'booking_confirmed';
    public const EVENT_BOOKING_PAID = 'booking_paid';
    public const EVENT_BOOKING_CANCELLED = 'booking_cancelled';
    public const EVENT_CARGO_BOOKED = 'cargo_booked';
    public const EVENT_CARGO_IN_TRANSIT = 'cargo_in_transit';
    public const EVENT_CARGO_DELIVERED = 'cargo_delivered';
    public const EVENT_INVESTOR_APPLICATION = 'investor_application';
    public const EVENT_INVESTOR_APPROVED = 'investor_approved';
    public const EVENT_INVESTOR_REJECTED = 'investor_rejected';
    public const EVENT_NEWSLETTER = 'newsletter';
    public const EVENT_CONTACT_FORM = 'contact_form';

    public const EVENTS = [
        self::EVENT_BOOKING_CREATED => 'Booking Created',
        self::EVENT_BOOKING_CONFIRMED => 'Booking Confirmed',
        self::EVENT_BOOKING_PAID => 'Booking Paid',
        self::EVENT_BOOKING_CANCELLED => 'Booking Cancelled',
        self::EVENT_CARGO_BOOKED => 'Cargo Booked',
        self::EVENT_CARGO_IN_TRANSIT => 'Cargo In Transit',
        self::EVENT_CARGO_DELIVERED => 'Cargo Delivered',
        self::EVENT_INVESTOR_APPLICATION => 'Investor Application Submitted',
        self::EVENT_INVESTOR_APPROVED => 'Investor Application Approved',
        self::EVENT_INVESTOR_REJECTED => 'Investor Application Rejected',
        self::EVENT_NEWSLETTER => 'Newsletter',
        self::EVENT_CONTACT_FORM => 'Contact Form Submission',
    ];

    public function getSubjectAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->subject_bn ?: $value;
        if ($locale === 'ar') return $this->subject_ar ?: $value;
        return $value;
    }

    public function getBodyAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->body_bn ?: $value;
        if ($locale === 'ar') return $this->body_ar ?: $value;
        return $value;
    }

    public function render(array $variables = []): array
    {
        $subject = $this->subject;
        $body = $this->body;

        foreach ($variables as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEvent($query, string $event)
    {
        return $query->where('event', $event);
    }
}
