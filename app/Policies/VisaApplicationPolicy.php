<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisaApplication;

class VisaApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function view(User $user, VisaApplication $application): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'agent']);
    }

    public function update(User $user, VisaApplication $application): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function delete(User $user, VisaApplication $application): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $application->status === 'draft';
    }

    public function approve(User $user, VisaApplication $application): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && 
               $application->status === 'government_processing';
    }

    public function reject(User $user, VisaApplication $application): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && 
               $application->status === 'government_processing';
    }
}
