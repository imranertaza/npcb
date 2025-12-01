<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $events = [
            [
                'event_category_id' => 1,
                'title'             => 'Tech Conference 2025',
                'slug'              => 'tech-conference-2025',
                'description'       => 'Annual technology conference focusing on AI, cloud, and modern web stacks.',
                'file'              => 'events/tech-conference.pdf',
                'createdBy'         => 1,
                'updatedBy'         => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'event_category_id' => 2,
                'title'             => 'Design Workshop',
                'slug' => 'design-workshop',
                'description'       => 'Hands-on workshop exploring UI/UX best practices and recruiter-grade dashboards.',
                'file'              => 'https://placehold.co/400x300?text=Football+Kick',
                'createdBy'         => 1,
                'updatedBy'         => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'event_category_id' => 3,
                'title'             => 'Startup Pitch Night',
                'slug' => 'startup-pitch-night',
                'description'       => 'Local startups present their ideas to investors and mentors.',
                'file'              => null, // optional file
                'createdBy'         => 1,
                'updatedBy'         => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        foreach ($events as $item) {
            Event::create($item);
        }
    }
}
