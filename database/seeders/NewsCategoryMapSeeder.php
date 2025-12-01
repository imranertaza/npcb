<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsCategoryMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::all()->each(function ($news) {
            $categories = NewsCategory::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray();
            $news->categories()->attach($categories);
        });
    }
}
