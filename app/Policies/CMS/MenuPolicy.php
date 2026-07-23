<?php

declare(strict_types=1);

namespace App\Policies\CMS;

use App\Models\CMS\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('manage_menus');
    }

    public function view(User $user, Menu $menu): bool
    {
        return $user->can('manage_menus');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_menus');
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->can('manage_menus');
    }

    public function delete(User $user, Menu $menu): bool
    {
        if (in_array($menu->slug, ['main', 'footer-1', 'footer-2', 'footer-3'])) {
            return false;
        }

        return $user->can('manage_menus');
    }
}
