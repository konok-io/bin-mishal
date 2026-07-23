<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function view(User $user, Customer $customer): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        if ($user->hasRole('agent') && $customer->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function update(User $user, Customer $customer): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        if ($user->hasRole('agent') && $customer->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }
}
