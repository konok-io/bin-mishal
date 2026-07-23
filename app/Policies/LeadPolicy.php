<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        return $lead->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        return $lead->assigned_to === $user->id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function convert(User $user, Lead $lead): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']) && 
               $lead->status !== 'converted';
    }
}
