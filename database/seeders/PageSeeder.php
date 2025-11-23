<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Page;
use Faker\Factory as Faker;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            $title = $faker->sentence(6);

            Page::create([
                'temp'              => 'default',
                'page_title'        => $title,
                'slug'              => Str::slug($title),
                'short_des'         => $faker->sentence(12),
                'page_description'  => collect($faker->paragraphs(3))->map(fn($p) => "<p>$p</p>")->implode("\n"),
                'f_image'           => 'https://placehold.co/600x400?text=Page+' . $i,
                'meta_title'        => $faker->sentence(5),
                'meta_description'  => $faker->sentence(20),
                'meta_keyword'      => implode(', ', $faker->words(6)),
                'status'            => $faker->randomElement(['Active', 'Inactive']),
                'createdBy'         => 1, // assuming admin user ID
                'updatedBy'         => 1,
            ]);
        }
    }
}
