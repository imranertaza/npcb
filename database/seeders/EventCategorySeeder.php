<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Upcoming Event',
                'description' => 'Future events happening soon',
            ],
            [
                'name' => 'Match Fixtures',
                'description' => 'Scheduled matches and dates',
            ],
            [
                'name' => 'Tournament Result',
                'description' => 'Final scores and standings',
            ],
            [
                'name' => 'Highlights',
                'description' => 'Key moments and summaries',
            ],
        ];

        foreach ($categories as $item) {
            DB::table('event_categories')->insert([
                'name' => $item['name'],
                'description' => $item['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
