<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing menus
        $headerMenu = Menu::where('position', 'Header')->first();
        $footerMenu = Menu::where('position', 'Footer')->first();

        // Header items
        $home = MenuItem::create([
            'menu_id'    => $headerMenu->id,
            'name'       => 'Home',
            'icon'       => 'fas fa-home',
            'link_type'  => 'page',
            'page_id'    => 1,
            'enabled'    => true,
            'order'      => 1,
        ]);

        $blog = MenuItem::create([
            'menu_id'      => $headerMenu->id,
            'name'         => 'Blog',
            'icon'         => 'fas fa-blog',
            'link_type'    => 'category',
            'category_id'  => 2,
            'enabled'      => true,
            'order'        => 2,
        ]);

        $contact = MenuItem::create([
            'menu_id'    => $headerMenu->id,
            'name'       => 'Contact',
            'icon'       => 'fas fa-envelope',
            'link_type'  => 'url',
            'url'        => '/contact',
            'enabled'    => true,
            'order'      => 3,
        ]);

        // Submenus under Blog
        MenuItem::create([
            'menu_id'     => $headerMenu->id,
            'parent_id'   => $blog->id,
            'name'        => 'Tech',
            'icon'        => 'fas fa-laptop',
            'link_type'   => 'category',
            'category_id' => 3,
            'enabled'     => true,
            'order'       => 1,
        ]);

        MenuItem::create([
            'menu_id'     => $headerMenu->id,
            'parent_id'   => $blog->id,
            'name'        => 'Lifestyle',
            'icon'        => 'fas fa-leaf',
            'link_type'   => 'category',
            'category_id' => 4,
            'enabled'     => true,
            'order'       => 2,
        ]);

        // Footer items
        MenuItem::create([
            'menu_id'    => $footerMenu->id,
            'name'       => 'Privacy Policy',
            'icon'       => 'fas fa-shield-alt',
            'link_type'  => 'url',
            'url'        => '/privacy-policy',
            'enabled'    => true,
            'order'      => 1,
        ]);

        MenuItem::create([
            'menu_id'    => $footerMenu->id,
            'name'       => 'Terms of Service',
            'icon'       => 'fas fa-file-contract',
            'link_type'  => 'url',
            'url'        => '/terms',
            'enabled'    => true,
            'order'      => 2,
        ]);
    }
}
