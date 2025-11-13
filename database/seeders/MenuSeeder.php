<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Header menu
        Menu::create([
            'name'     => 'Main Header',
            'position' => 'Header',
            'enabled'  => true,
        ]);

        // Create Footer menu
        Menu::create([
            'name'     => 'Footer Menu',
            'position' => 'Footer',
            'enabled'  => true,
        ]);

        // Create Sidebar menu
        Menu::create([
            'name'     => 'Sidebar Menu',
            'position' => 'Right Sidebar',
            'enabled'  => false,
        ]);

        // Create Floating Top menu
        Menu::create([
            'name'     => 'Floating Top Menu',
            'position' => 'Floating Top',
            'enabled'  => true,
        ]);
    }
}
