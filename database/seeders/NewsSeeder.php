<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            $title = $faker->sentence(6);
            News::create([
                'news_title'       => $title,
                'slug'             => Str::slug($title),
                'short_des'        => $faker->sentence(15),
                'description' => collect($faker->paragraphs(3))->map(fn($p) => "<p>$p</p>")->implode("\n"),
                'meta_title'       => $faker->sentence(5),
                'meta_keyword'     => implode(', ', $faker->words(6)),
                'meta_description' => $faker->sentence(20),
                'image'            => 'https://placehold.co/1400x400',
                'alt_name'         => $faker->words(3, true),
                'publish_date'     => now()->subDays(rand(0, 30)),
                'status'           => $faker->randomElement(['0', '1']),
                'createdBy'        => 1,
                'updatedBy'        => null,
            ]);
        }
    }
}
