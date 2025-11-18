<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notices = [
            [
                'title' => 'Office Will Remain Closed on Friday',
                'description' => 'The office will remain closed due to maintenance work.',
                'file' => 'notice_1.pdf',
            ],
            [
                'title' => 'New Policy Update for Employees',
                'description' => 'Please read the updated internal policies and guidelines.',
                'file' => 'policy_update.pdf',
            ],
            [
                'title' => 'Holiday Announcement',
                'description' => 'Upcoming public holiday notice for all staff members.',
                'file' => 'holiday_notice.pdf',
            ],
            [
                'title' => 'System Downtime Alert',
                'description' => 'Scheduled system maintenance tonight from 10 PM to 12 AM.',
                'file' => 'downtime_alert.png',
            ],
            [
                'title' => 'Training Session Reminder',
                'description' => 'All employees must attend the training session this Sunday.',
                'file' => 'training_schedule.docx',
            ],
        ];

        foreach ($notices as $notice) {
            DB::table('notices')->insert([
                'title' => $notice['title'],
                'description' => $notice['description'],
                'file' => $notice['file'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}