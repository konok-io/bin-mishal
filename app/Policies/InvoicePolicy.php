<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'accountant']);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'accountant']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'accountant']);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $invoice->status === 'draft';
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $invoice->status === 'draft';
    }

    public function send(User $user, Invoice $invoice): bool
    {
        return $user->hasRole(['admin', 'super_admin']) && $invoice->status === 'draft';
    }
}
