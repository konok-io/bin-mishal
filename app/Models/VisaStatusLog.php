<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaStatusLog extends Model
{
    use HasFactory;

    protected $table = 'visa_status_logs';

    protected $fillable = [
        'visa_application_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
        'notified_customer',
    ];

    protected $casts = [
        'notified_customer' => 'boolean',
    ];

    // Relationships
    public function visaApplication(): BelongsTo
    {
        return $this->belongsTo(VisaApplication::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
