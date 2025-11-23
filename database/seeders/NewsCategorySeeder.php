<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        // === ROOT NEWS CATEGORIES ===
        $politics   = $this->createCategory('Politics', null, 1, 'government,elections,policy');
        $business   = $this->createCategory('Business', null, 2, 'economy,markets,finance');
        $technology = $this->createCategory('Technology', null, 3, 'tech,innovation,startups');
        $sports     = $this->createCategory('Sports', null, 4, 'football,cricket,basketball');
        $entertainment = $this->createCategory('Entertainment', null, 5, 'movies,music,celebrities');
        $world      = $this->createCategory('World', null, 6, 'international,global,foreign affairs');
        $health     = $this->createCategory('Health', null, 7, 'medicine,fitness,wellness');
        $science    = $this->createCategory('Science', null, 8, 'space,research,discoveries');
        $lifestyle  = $this->createCategory('Lifestyle', null, 9, 'travel,food,culture');

        // === POLITICS SUBCATEGORIES ===
        $this->createCategory('Elections', $politics->id, 1, 'votes,campaigns,results');
        $this->createCategory('Government', $politics->id, 2, 'parliament,policy,law');
        $this->createCategory('Opinion', $politics->id, 3, 'editorials,analysis,commentary');

        // === BUSINESS SUBCATEGORIES ===
        $this->createCategory('Markets', $business->id, 1, 'stocks,forex,commodities');
        $this->createCategory('Economy', $business->id, 2, 'growth,inflation,trade');
        $this->createCategory('Companies', $business->id, 3, 'corporate,startups,earnings');

        // === TECHNOLOGY SUBCATEGORIES ===
        $this->createCategory('Gadgets', $technology->id, 1, 'smartphones,laptops,devices');
        $this->createCategory('AI & Innovation', $technology->id, 2, 'artificial intelligence,research');
        $this->createCategory('Startups', $technology->id, 3, 'funding,entrepreneurship');

        // === SPORTS SUBCATEGORIES ===
        $this->createCategory('Football', $sports->id, 1, 'soccer,world cup,league');
        $this->createCategory('Cricket', $sports->id, 2, 'ipl,test matches,odi');
        $this->createCategory('Basketball', $sports->id, 3, 'nba,college basketball');
        $this->createCategory('Olympics', $sports->id, 4, 'athletics,games,records');

        // === ENTERTAINMENT SUBCATEGORIES ===
        $this->createCategory('Movies', $entertainment->id, 1, 'hollywood,bollywood,reviews');
        $this->createCategory('Music', $entertainment->id, 2, 'albums,concerts,artists');
        $this->createCategory('Celebrities', $entertainment->id, 3, 'gossip,interviews,profiles');

        // === WORLD SUBCATEGORIES ===
        $this->createCategory('Asia', $world->id, 1, 'china,india,japan');
        $this->createCategory('Europe', $world->id, 2, 'uk,germany,france');
        $this->createCategory('Middle East', $world->id, 3, 'uae,saudi,iran');
        $this->createCategory('Americas', $world->id, 4, 'usa,latin america,canada');

        // === HEALTH SUBCATEGORIES ===
        $this->createCategory('Medicine', $health->id, 1, 'drugs,hospitals,research');
        $this->createCategory('Fitness', $health->id, 2, 'workout,yoga,diet');
        $this->createCategory('Mental Health', $health->id, 3, 'therapy,stress,awareness');

        // === SCIENCE SUBCATEGORIES ===
        $this->createCategory('Space', $science->id, 1, 'nasa,rockets,astronomy');
        $this->createCategory('Environment', $science->id, 2, 'climate,conservation,energy');
        $this->createCategory('Research', $science->id, 3, 'discoveries,studies,innovation');

        // === LIFESTYLE SUBCATEGORIES ===
        $this->createCategory('Travel', $lifestyle->id, 1, 'destinations,tips,adventure');
        $this->createCategory('Food', $lifestyle->id, 2, 'recipes,restaurants,cuisine');
        $this->createCategory('Culture', $lifestyle->id, 3, 'traditions,art,festivals');

        // === TOTAL: 9 Root + ~27 Subcategories ===
    }

    private function createCategory($name, $parentId, $sortOrder, $keywords = '')
    {
        return NewsCategory::firstOrCreate(
            ['category_name' => $name, 'parent_id' => $parentId],
            [
                'description'      => "$name related news and articles",
                'meta_title'       => $name,
                'meta_description' => "Latest $name news, updates and analysis",
                'meta_keyword'     => $keywords,
                'image'            => "https://placehold.co/600x400?text=" . urlencode($name),
                'alt_name'         => "$name Alt",
                'sort_order'       => $sortOrder,
                'status'           => '1',
                'createdBy'        => 1,
                'updatedBy'        => 1,
            ]
        );
    }
}
