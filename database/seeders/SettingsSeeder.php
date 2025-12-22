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
        $userId = 1;

        $settings = [
            // General

            ['label' => 'address', 'title' => 'Address', 'value' => '121 King St, Melbourne VIC 3000, United Kingdom', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'email', 'title' => 'Email', 'value' => 'stylemint@gmail.com', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'phone', 'title' => 'Phone', 'value' => '01714070770', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'state', 'title' => 'State', 'value' => '322', 'createdBy' => $userId, 'updatedBy' => $userId],
            // Branding
            ['label' => 'store_logo', 'title' => 'Store Logo', 'value' => 'settings/store_logo_1765197887_6936c83f8814c.png', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'store_icon', 'title' => 'Store Icon', 'value' => 'settings/store_icon_1765195985_6936c0d12bdaf.png', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'footer_logo', 'title' => 'Footer Logo', 'value' => 'settings/footer_logo_1765197832_6936c80849598.svg', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Mail settings
            ['label' => 'mail_protocol', 'title' => 'Mail Protocol', 'value' => 'smtp', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'mail_address', 'title' => 'Mail Address', 'value' => 'dnationsoftdm8@gmail.com', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_host', 'title' => 'SMTP Host', 'value' => 'smtp.mailtrap.io', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_username', 'title' => 'SMTP Username', 'value' => 'username', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_password', 'title' => 'SMTP Password', 'value' => 'password', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_port', 'title' => 'SMTP Port', 'value' => '587', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_timeout', 'title' => 'SMTP Timeout', 'value' => '30', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_crypto', 'title' => 'SMTP Crypto', 'value' => 'ssl', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Social links
            ['label' => 'fb_url', 'title' => 'Facebook', 'value' => 'https://www.facebook.com/', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'twitter_url', 'title' => 'Twitter', 'value' => 'https://twitter.com/', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'linkedin_url', 'title' => 'Linkedin', 'value' => 'https://www.linkedin.com/', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'instagram_url', 'title' => 'Instagram', 'value' => 'https://www.instagram.com/', 'createdBy' => $userId, 'updatedBy' => $userId],
            // SEO
            ['label' => 'meta_title', 'title' => 'Meta Title', 'value' => 'Style mint', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'meta_keyword', 'title' => 'Meta Keyword', 'value' => 'Style mint', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'meta_description', 'title' => 'Meta Description', 'value' => 'Style mint', 'createdBy' => $userId, 'updatedBy' => $userId]
        ];
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['label' => $setting['label']],
                $setting
            );
        }
    }
}
