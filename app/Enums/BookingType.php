<?php

declare(strict_types=1);

namespace App\Enums;

enum BookingType: string
{
    // Core booking types
    case TICKET = 'ticket';
    case UMRAH = 'umrah';
    case VISA = 'visa';
    case PACKAGE = 'package';
    
    // Additional booking types for Phase 3
    case SEAT = 'seat';              // Seat-based booking (flights, transport)
    case SCHEDULE = 'schedule';        // Time/Schedule-based booking
    case QUANTITY = 'quantity';        // Per-head/quantity pricing
    case APPOINTMENT = 'appointment';  // Appointment booking
    
    // Investment services
    case INVESTOR = 'investor';       // Investor consultation
    case CARGO = 'cargo';              // Cargo booking

    public function label(): string
    {
        return match ($this) {
            self::TICKET => 'Air Ticket',
            self::UMRAH => 'Umrah',
            self::VISA => 'Visa',
            self::PACKAGE => 'Package',
            self::SEAT => 'Seat Booking',
            self::SCHEDULE => 'Schedule Booking',
            self::QUANTITY => 'Per Head Booking',
            self::APPOINTMENT => 'Appointment',
            self::INVESTOR => 'Investor Services',
            self::CARGO => 'Cargo',
        };
    }
    
    /**
     * Get the icon for this booking type
     */
    public function icon(): string
    {
        return match ($this) {
            self::TICKET => 'fas fa-plane',
            self::UMRAH => 'fas fa-kaaba',
            self::VISA => 'fas fa-passport',
            self::PACKAGE => 'fas fa-box',
            self::SEAT => 'fas fa-chair',
            self::SCHEDULE => 'fas fa-clock',
            self::QUANTITY => 'fas fa-users',
            self::APPOINTMENT => 'fas fa-calendar-check',
            self::INVESTOR => 'fas fa-chart-line',
            self::CARGO => 'fas fa-boxes-stacked',
        };
    }
    
    /**
     * Get the color for this booking type
     */
    public function color(): string
    {
        return match ($this) {
            self::TICKET => '#006C35',
            self::UMRAH => '#C8A951',
            self::VISA => '#1B3A5C',
            self::PACKAGE => '#059669',
            self::SEAT => '#6366F1',
            self::SCHEDULE => '#8B5CF6',
            self::QUANTITY => '#EC4899',
            self::APPOINTMENT => '#14B8A6',
            self::INVESTOR => '#F59E0B',
            self::CARGO => '#64748B',
        };
    }
    
    /**
     * Get associated service key
     */
    public function serviceKey(): string
    {
        return match ($this) {
            self::TICKET => 'flight',
            self::UMRAH => 'umrah',
            self::VISA => 'visa',
            self::PACKAGE => 'umrah',
            self::SEAT => 'flight',
            self::SCHEDULE => 'appointment',
            self::QUANTITY => 'cargo',
            self::APPOINTMENT => 'appointment',
            self::INVESTOR => 'investor',
            self::CARGO => 'cargo',
        };
    }
}
