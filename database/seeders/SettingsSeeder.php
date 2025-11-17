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
            ['label' => 'invoice_prefix', 'title' => 'Invoice Prefix', 'value' => 'FL-INV-', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'currency_symbol', 'title' => 'Currency Symbol', 'value' => '$', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'currency', 'title' => 'Currency', 'value' => 'USD', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'theme', 'title' => 'Theme', 'value' => 'Theme_3', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Store info
            ['label' => 'store_name', 'title' => 'Store Name', 'value' => 'Style mint', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'store_owner', 'title' => 'Store Owner', 'value' => 'admin', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'address', 'title' => 'Address', 'value' => '121 King St, Melbourne VIC 3000, United Kingdom', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'email', 'title' => 'Email', 'value' => 'stylemint@gmail.com', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'phone', 'title' => 'Phone', 'value' => '01714070770', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'country', 'title' => 'Country', 'value' => '18', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'state', 'title' => 'State', 'value' => '322', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'language', 'title' => 'Language', 'value' => 'English', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'length_class', 'title' => 'Length Class', 'value' => 'Inch', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'weight_class', 'title' => 'Weight Class', 'value' => 'Gram', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Branding
            ['label' => 'store_logo', 'title' => 'Store Logo', 'value' => 'logo.png', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'store_icon', 'title' => 'Store Icon', 'value' => 'icon.png', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Mail settings
            ['label' => 'mail_protocol', 'title' => 'Mail Protocol', 'value' => 'smtp', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'mail_address', 'title' => 'Mail Address', 'value' => 'dnationsoftdm8@gmail.com', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_host', 'title' => 'SMTP Host', 'value' => 'smtp.mailtrap.io', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_username', 'title' => 'SMTP Username', 'value' => 'username', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_password', 'title' => 'SMTP Password', 'value' => 'password', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_port', 'title' => 'SMTP Port', 'value' => '587', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_timeout', 'title' => 'SMTP Timeout', 'value' => '30', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'smtp_crypto', 'title' => 'SMTP Crypto', 'value' => 'ssl', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Alerts
            ['label' => 'new_account_alert_mail', 'title' => 'New Account Alert Mail', 'value' => '1', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'new_order_alert_mail', 'title' => 'New Order Alert Mail', 'value' => '1', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Social links
            ['label' => 'fb_url', 'title' => 'Facebook', 'value' => '#', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'twitter_url', 'title' => 'Twitter', 'value' => '#', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'tiktok_url', 'title' => 'Tiktok', 'value' => '#', 'createdBy' => $userId, 'updatedBy' => $userId],
            ['label' => 'instagram_url', 'title' => 'Instagram', 'value' => '#', 'createdBy' => $userId, 'updatedBy' => $userId],

            // Catalog
            ['label' => 'category_product_limit', 'title' => 'Category Product Limit', 'value' => '8', 'createdBy' => $userId, 'updatedBy' => $userId],

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
