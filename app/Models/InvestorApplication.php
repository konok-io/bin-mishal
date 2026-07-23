<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvestorApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_no',
        'service_id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'company_name',
        'investment_range',
        'investment_amount',
        'nationality',
        'passport_no',
        ' documents',
        'status',
        'status_notes',
        'assigned_to',
        'reviewed_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'documents' => 'array',
        'status' => InvestorApplicationStatus::class,
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(InvestorService::class, 'service_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function generateApplicationNo(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $lastApp = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $lastNumber = $lastApp ? intval(substr($lastApp->application_no, -5)) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}-{$newNumber}";
    }

    public function approve(?string $notes = null): void
    {
        $this->update([
            'status' => InvestorApplicationStatus::APPROVED,
            'status_notes' => $notes,
            'approved_at' => now(),
            'reviewed_at' => now(),
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => InvestorApplicationStatus::REJECTED,
            'status_notes' => $reason,
            'rejected_at' => now(),
            'reviewed_at' => now(),
        ]);
    }

    public function assignTo(User $user): void
    {
        $this->update(['assigned_to' => $user->id]);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', InvestorApplicationStatus::SUBMITTED);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', InvestorApplicationStatus::UNDER_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', InvestorApplicationStatus::APPROVED);
    }
}
