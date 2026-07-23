<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function view(User $user, Booking $booking): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        if ($booking->customer && $booking->customer->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function issue(User $user, Booking $booking): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $booking->booking_status === 'pending';
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && 
               in_array($booking->booking_status, ['pending', 'confirmed']);
    }
}
