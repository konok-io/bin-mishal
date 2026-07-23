<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone_country_code',
        'subject',
        'message',
        'type',
        'is_read',
        'read_at',
        'read_by',
        'is_replied',
        'replied_at',
        'replied_by',
        'reply_note',
        'admin_notes',
        'is_spam',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
        'is_spam' => 'boolean',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public const TYPE_GENERAL = 'general';
    public const TYPE_BOOKING = 'booking';
    public const TYPE_CARGO = 'cargo';
    public const TYPE_VISA = 'visa';
    public const TYPE_INVESTOR = 'investor';
    public const TYPE_COMPLAINT = 'complaint';
    public const TYPE_FEEDBACK = 'feedback';
    public const TYPE_TESTIMONIAL = 'testimonial';
    public const TYPE_OTHER = 'other';

    public const TYPES = [
        self::TYPE_GENERAL => 'General Inquiry',
        self::TYPE_BOOKING => 'Booking Inquiry',
        self::TYPE_CARGO => 'Cargo Inquiry',
        self::TYPE_VISA => 'Visa Inquiry',
        self::TYPE_INVESTOR => 'Investor Inquiry',
        self::TYPE_COMPLAINT => 'Complaint',
        self::TYPE_FEEDBACK => 'Feedback',
        self::TYPE_TESTIMONIAL => 'Testimonial Submission',
        self::TYPE_OTHER => 'Other',
    ];

    public function reader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function replier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false)->where('is_spam', false);
    }

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
                'read_by' => auth()->id(),
            ]);
        }
    }

    public function markAsReplied(?string $note = null): void
    {
        $this->update([
            'is_replied' => true,
            'replied_at' => now(),
            'replied_by' => auth()->id(),
            'reply_note' => $note,
        ]);
    }

    public function markAsSpam(): void
    {
        $this->update(['is_spam' => true]);
    }
}
