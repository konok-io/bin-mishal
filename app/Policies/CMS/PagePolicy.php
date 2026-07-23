<?php

declare(strict_types=1);

namespace App\Policies\CMS;

use App\Models\CMS\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('manage_pages');
    }

    public function view(User $user, Page $page): bool
    {
        return $user->can('manage_pages');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_pages');
    }

    public function update(User $user, Page $page): bool
    {
        return $user->can('manage_pages');
    }

    public function delete(User $user, Page $page): bool
    {
        // Prevent deleting system pages or homepage
        if ($page->is_system || $page->is_homepage) {
            return false;
        }

        return $user->can('manage_pages');
    }
}
