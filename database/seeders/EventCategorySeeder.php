<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // === ROOT EVENT CATEGORIES ===
        $music        = $this->createCategory('Music', null, 1, 'concerts,live music,festivals');
        $sports       = $this->createCategory('Sports', null, 2, 'football,cricket,basketball');
        $business     = $this->createCategory('Business', null, 3, 'corporate,entrepreneurship,markets');
        $technology   = $this->createCategory('Technology', null, 4, 'gadgets,innovation,startups');
        $entertainment = $this->createCategory('Entertainment', null, 5, 'movies,music,celebrities');
        $health       = $this->createCategory('Health', null, 6, 'wellness,fitness,medicine');
        $lifestyle    = $this->createCategory('Lifestyle', null, 7, 'travel,food,culture');
        
        // === MUSIC SUBCATEGORIES ===
        $this->createCategory('Concerts', $music->id, 1, 'live,concerts,touring');
        $this->createCategory('Festivals', $music->id, 2, 'music festival,events,tickets');
        $this->createCategory('Artists', $music->id, 3, 'musicians,performers,bands');
        
        // === SPORTS SUBCATEGORIES ===
        $this->createCategory('Football', $sports->id, 1, 'soccer,league,world cup');
        $this->createCategory('Basketball', $sports->id, 2, 'nba,college basketball,hoops');
        $this->createCategory('Cricket', $sports->id, 3, 'ipl,t20,world cup');
        
        // === BUSINESS SUBCATEGORIES ===
        $this->createCategory('Markets', $business->id, 1, 'stocks,forex,commodities');
        $this->createCategory('Startups', $business->id, 2, 'entrepreneurship,funding,investments');
        $this->createCategory('Corporate', $business->id, 3, 'm&a,finance,corporate news');
        
        // === TECHNOLOGY SUBCATEGORIES ===
        $this->createCategory('Gadgets', $technology->id, 1, 'smartphones,laptops,wearables');
        $this->createCategory('Innovation', $technology->id, 2, 'AI,blockchain,5G');
        $this->createCategory('Startups', $technology->id, 3, 'tech startups,VC,entrepreneurship');
        
        // === ENTERTAINMENT SUBCATEGORIES ===
        $this->createCategory('Movies', $entertainment->id, 1, 'blockbusters,indie films,hollywood');
        $this->createCategory('Music', $entertainment->id, 2, 'albums,concerts,artists');
        $this->createCategory('Celebrities', $entertainment->id, 3, 'gossip,interviews,profiles');
        
        // === HEALTH SUBCATEGORIES ===
        $this->createCategory('Fitness', $health->id, 1, 'workouts,yoga,diet');
        $this->createCategory('Mental Health', $health->id, 2, 'therapy,stress management,mental well-being');
        $this->createCategory('Medicine', $health->id, 3, 'research,hospitals,treatments');
        
        // === LIFESTYLE SUBCATEGORIES ===
        $this->createCategory('Travel', $lifestyle->id, 1, 'destinations,adventure,travel tips');
        $this->createCategory('Food', $lifestyle->id, 2, 'recipes,restaurants,cooking');
        $this->createCategory('Culture', $lifestyle->id, 3, 'art,traditions,festivals');
        
        // === TOTAL: 7 Root + ~21 Subcategories ===
    }

    private function createCategory($name, $parentId, $sortOrder, $keywords = '')
    {
        return EventCategory::firstOrCreate(
            ['category_name' => $name, 'parent_id' => $parentId],
            [
                'description'      => "$name related events and articles",
                'meta_title'       => $name,
                'meta_description' => "Latest $name news, updates, and events",
                'meta_keyword'     => $keywords,
                'image'            => "https://placehold.co/600x400?text=" . urlencode($name),
                'alt_name'         => "$name Alt",
                'sort_order'       => $sortOrder,
                'status'           => '1', // Active status
                'createdBy'        => 1,  // Assuming user ID 1 is the creator
                'updatedBy'        => 1,  // Assuming user ID 1 is the updater
            ]
        );
    }
}
