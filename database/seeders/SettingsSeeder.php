<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1; // Replace with dynamic user ID if needed

        $settings = [
            [
                'label' => 'site_name',
                'title' => 'Site Name',
                'value' => 'My Awesome App',
                'createdBy' => $userId,
                'updatedBy' => $userId,
            ],
            [
                'label' => 'site_email',
                'title' => 'Site Email',
                'value' => 'support@example.com',
                'createdBy' => $userId,
                'updatedBy' => $userId,
            ],
            [
                'label' => 'site_phone',
                'title' => 'Site Phone',
                'value' => '+880123456789',
                'createdBy' => $userId,
                'updatedBy' => $userId,
            ],
            [
                'label' => 'favicon',
                'title' => 'Favicon',
                'value' => 'favicon.ico', // path or filename
                'createdBy' => $userId,
                'updatedBy' => $userId,
            ],
            [
                'label' => 'logo',
                'title' => 'Logo',
                'value' => 'logo.png', // path or filename
                'createdBy' => $userId,
                'updatedBy' => $userId,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
