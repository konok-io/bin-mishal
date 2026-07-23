<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'booking_types',
        'settings',
        'form_fields',
        'is_enabled',
        'min_quantity',
        'max_quantity',
        'currency',
        'pricing_model',
        'requires_confirmation',
        'allow_cancellation',
        'cancellation_deadline_days',
    ];

    protected $casts = [
        'booking_types' => 'array',
        'settings' => 'array',
        'form_fields' => 'array',
        'is_enabled' => 'boolean',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'requires_confirmation' => 'boolean',
        'allow_cancellation' => 'boolean',
        'cancellation_deadline_days' => 'integer',
    ];

    // Service types
    public const SERVICE_FLIGHT = 'flight';
    public const SERVICE_UMRAH = 'umrah';
    public const SERVICE_VISA = 'visa';
    public const SERVICE_CARGO = 'cargo';
    public const SERVICE_APPOINTMENT = 'appointment';
    public const SERVICE_INVESTOR = 'investor';

    public const SERVICES = [
        self::SERVICE_FLIGHT => 'Flight',
        self::SERVICE_UMRAH => 'Umrah',
        self::SERVICE_VISA => 'Visa',
        self::SERVICE_CARGO => 'Cargo',
        self::SERVICE_APPOINTMENT => 'Appointment',
        self::SERVICE_INVESTOR => 'Investor',
    ];

    // Default booking types per service
    public const DEFAULT_BOOKING_TYPES = [
        self::SERVICE_FLIGHT => [BookingType::TICKET, BookingType::SEAT],
        self::SERVICE_UMRAH => [BookingType::UMRAH, BookingType::PACKAGE],
        self::SERVICE_VISA => [BookingType::VISA],
        self::SERVICE_CARGO => [BookingType::CARGO, BookingType::QUANTITY],
        self::SERVICE_APPOINTMENT => [BookingType::SCHEDULE, BookingType::APPOINTMENT],
        self::SERVICE_INVESTOR => [BookingType::INVESTOR],
    ];

    // Pricing models
    public const PRICING_FIXED = 'fixed';
    public const PRICING_PER_UNIT = 'per_unit';
    public const PRICING_TIERED = 'tiered';
    public const PRICING_HYBRID = 'hybrid';

    public const PRICING_MODELS = [
        self::PRICING_FIXED => 'Fixed Price',
        self::PRICING_PER_UNIT => 'Per Unit',
        self::PRICING_TIERED => 'Tiered Pricing',
        self::PRICING_HYBRID => 'Hybrid (Fixed + Per Unit)',
    ];

    // Default form fields
    public const FORM_FIELDS = [
        'name' => ['type' => 'text', 'required' => true, 'label' => 'Full Name'],
        'email' => ['type' => 'email', 'required' => true, 'label' => 'Email'],
        'phone' => ['type' => 'tel', 'required' => true, 'label' => 'Phone'],
        'nationality' => ['type' => 'select', 'required' => true, 'label' => 'Nationality'],
        'passport_no' => ['type' => 'text', 'required' => false, 'label' => 'Passport Number'],
        'passport_expiry' => ['type' => 'date', 'required' => false, 'label' => 'Passport Expiry'],
        'date_of_birth' => ['type' => 'date', 'required' => false, 'label' => 'Date of Birth'],
        'quantity' => ['type' => 'number', 'required' => true, 'label' => 'Quantity'],
        'preferred_date' => ['type' => 'date', 'required' => false, 'label' => 'Preferred Date'],
        'notes' => ['type' => 'textarea', 'required' => false, 'label' => 'Notes'],
    ];

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeByService($query, string $service)
    {
        return $query->where('service_type', $service);
    }

    public function isBookingTypeEnabled(BookingType $type): bool
    {
        $types = $this->booking_types ?? [];
        return in_array($type->value, $types);
    }

    public function getFormField(string $field): ?array
    {
        return $this->form_fields[$field] ?? null;
    }

    public function getRequiredFields(): array
    {
        $fields = $this->form_fields ?? self::FORM_FIELDS;
        
        return collect($fields)
            ->filter(fn($field) => $field['required'] ?? false)
            ->keys()
            ->toArray();
    }

    /**
     * Get or create configuration for a service
     */
    public static function getForService(string $service): self
    {
        return self::firstOrCreate(
            ['service_type' => $service],
            [
                'booking_types' => array_map(fn($t) => $t->value, self::DEFAULT_BOOKING_TYPES[$service] ?? []),
                'settings' => [],
                'form_fields' => self::FORM_FIELDS,
                'is_enabled' => true,
                'min_quantity' => 1,
                'max_quantity' => 10,
                'currency' => 'SAR',
                'pricing_model' => self::PRICING_FIXED,
                'requires_confirmation' => true,
                'allow_cancellation' => true,
                'cancellation_deadline_days' => 7,
            ]
        );
    }

    /**
     * Initialize default configurations for all services
     */
    public static function initializeDefaults(): void
    {
        foreach (self::SERVICES as $key => $name) {
            self::getForService($key);
        }
    }
}
