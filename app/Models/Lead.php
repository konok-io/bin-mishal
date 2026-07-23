<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'whatsapp',
        'email',
        'service_interest',
        'source',
        'status',
        'assigned_to',
        'follow_up_date',
        'conversion_probability',
        'lost_reason',
        'converted_customer_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
            'follow_up_date' => 'date',
            'conversion_probability' => 'integer',
        ];
    }

    // Relationships
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function convertedCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'converted_customer_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class);
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', LeadStatus::NEW);
    }

    public function scopeContacted($query)
    {
        return $query->where('status', LeadStatus::CONTACTED);
    }

    public function scopeQualified($query)
    {
        return $query->where('status', LeadStatus::QUALIFIED);
    }

    public function scopeConverted($query)
    {
        return $query->where('status', LeadStatus::CONVERTED);
    }

    public function scopeLost($query)
    {
        return $query->where('status', LeadStatus::LOST);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('follow_up_date', today());
    }

    // Methods
    public function markAsContacted(): void
    {
        $this->update(['status' => LeadStatus::CONTACTED]);
    }

    public function markAsQualified(): void
    {
        $this->update(['status' => LeadStatus::QUALIFIED]);
    }

    public function convertToCustomer(array $customerData): Customer
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'user_type' => 'customer',
            'password' => bcrypt(bin2hex(random_bytes(8))),
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_code' => Customer::generateCode(),
            'source' => 'lead',
            'assigned_to' => $this->assigned_to,
            ...$customerData,
        ]);

        $this->update([
            'status' => LeadStatus::CONVERTED,
            'converted_customer_id' => $customer->id,
        ]);

        return $customer;
    }

    public function markAsLost(string $reason): void
    {
        $this->update([
            'status' => LeadStatus::LOST,
            'lost_reason' => $reason,
        ]);
    }

    public function addActivity(array $activityData): LeadActivity
    {
        return $this->activities()->create($activityData);
    }
}
