<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_no',
        'name',
        'name_ar',
        'id_type',
        'id_number',
        'nationality',
        'phone',
        'email',
        'company',
        'status',
        'registered_by',
        'document_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate a unique tracking number.
     */
    public static function generateTrackingNo(): string
    {
        $prefix = 'BMT';
        $year = date('Y');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Get the user who registered this customer.
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get the services for this registration.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'customer_registration_services')
            ->withPivot(['name', 'amount'])
            ->withTimestamps();
    }

    /**
     * Scope for active registrations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'revoked');
    }
}
