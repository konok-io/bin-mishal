<?php

namespace Database\Seeders;

use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Header Menu', 'slug' => 'header-menu', 'location' => 'header', 'status' => true],
            ['name' => 'Footer Column 1', 'slug' => 'footer-col1', 'location' => 'footer_col1', 'status' => true],
        ];
        foreach ($menus as $menuData) {
            $menu = Menu::updateOrCreate(['slug' => $menuData['slug']], $menuData);
            $items = $menu->location === 'header' 
                ? [['title' => 'Home', 'url' => '/', 'order' => 1], ['title' => 'About', 'url' => '/about', 'order' => 2], ['title' => 'Services', 'url' => '/services', 'order' => 3], ['title' => 'Contact', 'url' => '/contact', 'order' => 4]]
                : [['title' => 'About Us', 'url' => '/about', 'order' => 1], ['title' => 'Our Team', 'url' => '/team', 'order' => 2]];
            foreach ($items as $itemData) {
                MenuItem::updateOrCreate(['menu_id' => $menu->id, 'title' => $itemData['title']], ['url' => $itemData['url'], 'order' => $itemData['order'], 'status' => true]);
            }
        }
        $this->command->info('MenuSeeder: Created sample menus with items!');
    }
}
