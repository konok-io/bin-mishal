<?php

declare(strict_types=1);

namespace App\Services\CMS;

use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MenuBuilder
{
    /**
     * Get menu items tree for a specific location.
     */
    public function getMenuTree(string $location): ?Collection
    {
        $menu = Menu::getByLocation($location);

        if (!$menu) {
            return null;
        }

        return $menu->getTree();
    }

    /**
     * Render menu as nested array for frontend.
     */
    public function renderMenu(string $location): array
    {
        $items = $this->getMenuTree($location);

        if (!$items) {
            return [];
        }

        return $this->buildMenuArray($items);
    }

    /**
     * Build menu array recursively.
     */
    protected function buildMenuArray(Collection $items): array
    {
        $result = [];

        foreach ($items as $item) {
            // Skip login menu items if user is already logged in for that guard
            if ($this->shouldSkipMenuItem($item)) {
                continue;
            }

            $menuItem = [
                'id' => $item->id,
                'title' => $item->translated_title,
                'description' => $item->translated_description,
                'url' => $item->resolveUrl(),
                'target' => $item->target,
                'icon' => $item->icon,
                'css_class' => $item->css_class,
                'badge_text' => $item->translated_badge_text,
                'badge_color' => $item->badge_color,
                'is_mega' => $item->is_mega,
                'mega_column' => $item->mega_column,
                'is_active' => $item->isActive(),
                'children' => [],
            ];

            if ($item->children && $item->children->isNotEmpty()) {
                $menuItem['children'] = $this->buildMenuArray($item->children);
            }

            $result[] = $menuItem;
        }

        return $result;
    }

    /**
     * Check if a menu item should be skipped based on authentication status.
     */
    protected function shouldSkipMenuItem(MenuItem $item): bool
    {
        $url = ltrim($item->url ?? '', '/');

        // Admin login - skip if admin is logged in
        if (in_array($url, ['admin', 'admin/login'])) {
            return Auth::guard('admin')->check();
        }

        // Employee login - skip if employee is logged in
        if (in_array($url, ['employee', 'employee/login'])) {
            return Auth::guard('employee')->check();
        }

        // Portal login/register - skip if web user is logged in
        if (in_array($url, ['portal', 'portal/login', 'portal/register'])) {
            return Auth::guard('web')->check();
        }

        return false;
    }

    /**
     * Get dashboard URL based on user guard.
     */
    public function getDashboardUrl(): ?string
    {
        if (Auth::guard('admin')->check()) {
            return '/admin/dashboard';
        }

        if (Auth::guard('employee')->check()) {
            $locale = app()->getLocale() ?: config('app.locale', 'bn');
            return "/{$locale}/employee/dashboard";
        }

        if (Auth::guard('web')->check()) {
            $locale = app()->getLocale() ?: config('app.locale', 'bn');
            return "/{$locale}/portal/dashboard";
        }

        return null;
    }

    /**
     * Get logged in user type for menu display.
     */
    public function getLoggedInUserType(): ?string
    {
        if (Auth::guard('admin')->check()) {
            return 'admin';
        }

        if (Auth::guard('employee')->check()) {
            return 'employee';
        }

        if (Auth::guard('web')->check()) {
            return 'customer';
        }

        return null;
    }

    /**
     * Get header menu.
     */
    public function header(): array
    {
        return $this->renderMenu(Menu::LOCATION_HEADER);
    }

    /**
     * Get footer column 1 menu.
     */
    public function footerCol1(): array
    {
        return $this->renderMenu(Menu::LOCATION_FOOTER_COL1);
    }

    /**
     * Get footer column 2 menu.
     */
    public function footerCol2(): array
    {
        return $this->renderMenu(Menu::LOCATION_FOOTER_COL2);
    }

    /**
     * Get footer column 3 menu.
     */
    public function footerCol3(): array
    {
        return $this->renderMenu(Menu::LOCATION_FOOTER_COL3);
    }

    /**
     * Get footer bottom bar.
     */
    public function footerBottom(): array
    {
        return $this->renderMenu(Menu::LOCATION_FOOTER_BOTTOM);
    }

    /**
     * Get mobile menu.
     */
    public function mobile(): array
    {
        return $this->renderMenu(Menu::LOCATION_MOBILE);
    }

    /**
     * Get top bar menu.
     */
    public function topBar(): array
    {
        return $this->renderMenu(Menu::LOCATION_TOP_BAR);
    }

    /**
     * Get mega services menu items.
     */
    public function megaServices(): array
    {
        return $this->renderMenu(Menu::LOCATION_MEGA_SERVICES);
    }

    /**
     * Get mega about menu items.
     */
    public function megaAbout(): array
    {
        return $this->renderMenu(Menu::LOCATION_MEGA_ABOUT);
    }

    /**
     * Create default menus if they don't exist.
     */
    public function createDefaultMenus(): void
    {
        $this->createMenu('Main Navigation', 'main', Menu::LOCATION_HEADER);
        $this->createMenu('Footer Links 1', 'footer-1', Menu::LOCATION_FOOTER_COL1);
        $this->createMenu('Footer Links 2', 'footer-2', Menu::LOCATION_FOOTER_COL2);
        $this->createMenu('Footer Links 3', 'footer-3', Menu::LOCATION_FOOTER_COL3);
        $this->createMenu('Mobile Menu', 'mobile', Menu::LOCATION_MOBILE);
        $this->createMenu('Top Bar', 'top-bar', Menu::LOCATION_TOP_BAR);
    }

    protected function createMenu(string $name, string $slug, string $location): ?Menu
    {
        return Menu::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'location' => $location,
                'status' => true,
            ]
        );
    }

    /**
     * Reorder menu items.
     */
    public function reorderItems(Menu $menu, array $order): void
    {
        foreach ($order as $index => $itemId) {
            MenuItem::where('id', $itemId)
                ->where('menu_id', $menu->id)
                ->update(['order' => $index]);
        }

        Menu::clearCache($menu->id);
    }

    /**
     * Move a menu item to a new parent.
     */
    public function moveItem(MenuItem $item, ?int $newParentId): void
    {
        $item->update(['parent_id' => $newParentId]);
        Menu::clearCache($item->menu_id);
    }
}
