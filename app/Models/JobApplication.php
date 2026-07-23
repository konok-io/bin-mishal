<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'full_name',
        'email',
        'phone',
        'phone_country_code',
        'cover_letter',
        'cv_path',
        'status',
        'applied_at',
        'reviewed_by',
        'reviewed_at',
        'interview_date',
        'interview_notes',
        'rejection_reason',
        'admin_notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'interview_date' => 'datetime',
    ];

    public const STATUS_RECEIVED = 'received';
    public const STATUS_SHORTLISTED = 'shortlisted';
    public const STATUS_INTERVIEW = 'interview';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_HIRED = 'hired';

    public const STATUSES = [
        self::STATUS_RECEIVED => 'Received',
        self::STATUS_SHORTLISTED => 'Shortlisted',
        self::STATUS_INTERVIEW => 'Interview Scheduled',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_HIRED => 'Hired',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [
            self::STATUS_RECEIVED,
            self::STATUS_SHORTLISTED,
            self::STATUS_INTERVIEW,
        ]);
    }

    public function markAsShortlisted(): void
    {
        $this->update([
            'status' => self::STATUS_SHORTLISTED,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
    }

    public function markAsInterview(\DateTime $date = null, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_INTERVIEW,
            'interview_date' => $date,
            'interview_notes' => $notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
    }

    public function markAsRejected(?string $reason = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
    }

    public function markAsHired(): void
    {
        $this->update([
            'status' => self::STATUS_HIRED,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
    }
}
