<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            $title = $faker->sentence(6);
            Post::create([
                'post_title'       => $title,
                'slug'             => Str::slug($title),
                'short_des'        => $faker->sentence(15),
                'description' => collect($faker->paragraphs(3))->map(fn($p) => "<p>$p</p>")->implode("\n"),
                'meta_title'       => $faker->sentence(5),
                'meta_keyword'     => implode(', ', $faker->words(6)),
                'meta_description' => $faker->sentence(20),
                'image'            => 'https://placehold.co/1400x400',
                'alt_name'         => $faker->words(3, true),
                'video_id'         => $faker->boolean ? 'yt-' . Str::random(8) : null,
                'publish_date'     => now()->subDays(rand(0, 30)),
                'status'           => $faker->randomElement(['0', '1']),
                'createdBy'        => 1, // assuming admin ID = 1
                'updatedBy'        => null,
            ]);
        }
    }
}
