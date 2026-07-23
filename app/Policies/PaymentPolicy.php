<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'accountant']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'accountant']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'accountant']);
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $payment->status === 'pending';
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $payment->status === 'pending';
    }

    public function verify(User $user, Payment $payment): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $payment->status === 'pending';
    }

    public function refund(User $user, Payment $payment): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $payment->status === 'completed';
    }
}
