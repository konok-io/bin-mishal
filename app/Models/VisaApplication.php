<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VisaStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class VisaApplication extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected static $logName = 'visa_application';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['application_no', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'application_no',
        'customer_id',
        'visa_type_id',
        'applicant_name',
        'passport_no',
        'iqama_no',
        'sponsor_name',
        'sponsor_id',
        'travel_date',
        'return_date',
        'purpose',
        'status',
        'current_stage',
        'assigned_to',
        'government_reference_no',
        'government_fee',
        'service_fee',
        'total_amount',
        'paid_amount',
        'submission_date',
        'expected_date',
        'completion_date',
        'rejection_reason',
        'remarks',
    ];

    protected $casts = [
        'government_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'travel_date' => 'date',
        'return_date' => 'date',
        'submission_date' => 'date',
        'expected_date' => 'date',
        'completion_date' => 'date',
        'status' => VisaStatus::class,
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function visaType(): BelongsTo
    {
        return $this->belongsTo(VisaType::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VisaApplicationDocument::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(VisaStatusLog::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', VisaStatus::DRAFT);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', VisaStatus::SUBMITTED);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [
            VisaStatus::SUBMITTED,
            VisaStatus::DOCUMENT_PENDING,
            VisaStatus::UNDER_REVIEW,
            VisaStatus::GOVERNMENT_PROCESSING,
        ]);
    }

    // Methods
    public static function generateNo(): string
    {
        return 'VISA-' . date('ymd') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function submit(): void
    {
        $this->update([
            'status' => VisaStatus::SUBMITTED,
            'submission_date' => now(),
        ]);

        $this->logStatusChange(VisaStatus::DRAFT, VisaStatus::SUBMITTED);
    }

    public function approve(): void
    {
        $this->update([
            'status' => VisaStatus::APPROVED,
            'completion_date' => now(),
        ]);

        $this->logStatusChange(null, VisaStatus::APPROVED);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => VisaStatus::REJECTED,
            'rejection_reason' => $reason,
            'completion_date' => now(),
        ]);

        $this->logStatusChange(null, VisaStatus::REJECTED);
    }

    public function deliver(): void
    {
        $this->update(['status' => VisaStatus::DELIVERED]);
        $this->logStatusChange(VisaStatus::APPROVED, VisaStatus::DELIVERED);
    }

    protected function logStatusChange(?VisaStatus $from, VisaStatus $to): void
    {
        $this->statusLogs()->create([
            'from_status' => $from?->value,
            'to_status' => $to->value,
            'changed_by' => auth()->id(),
        ]);
    }
}
