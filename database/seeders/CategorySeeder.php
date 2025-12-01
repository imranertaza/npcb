<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run(): void
    {
        // === ROOT CATEGORIES ===
        $electronics = $this->createCategory('Electronics', null, 1, 'electronics,gadgets,devices');
        $fashion     = $this->createCategory('Fashion', null, 2, 'clothing,shoes,accessories');
        $books       = $this->createCategory('Books', null, 3, 'books,novels,literature');
        $home        = $this->createCategory('Home & Living', null, 4, 'furniture,decor,appliances');
        $sports      = $this->createCategory('Sports', null, 5, 'sports,gear,equipment');
        $toys        = $this->createCategory('Toys', null, 6, 'toys,games,kids');

        // === ELECTRONICS SUBCATEGORIES (6) ===
        $mobiles   = $this->createCategory('Mobile Phones', $electronics->id, 1, 'smartphones,mobile,phones');
        $laptops   = $this->createCategory('Laptops', $electronics->id, 2, 'laptops,notebooks,computers');
        $this->createCategory('Tablets', $electronics->id, 3, 'tablets,ipad,android');
        $this->createCategory('TVs', $electronics->id, 4, 'televisions,smart tv,oled');
        $this->createCategory('Cameras', $electronics->id, 5, 'dslr,mirrorless,camera');
        $this->createCategory('Headphones', $electronics->id, 6, 'earphones,wireless,bluetooth');

        // Sub-sub under Mobile Phones
        $this->createCategory('Android Phones', $mobiles->id, 1, 'samsung,google,oneplus');
        $this->createCategory('iPhones', $mobiles->id, 2, 'apple,iphone 15,iphone 16');

        // === FASHION SUBCATEGORIES (8) ===
        $men   = $this->createCategory('Men', $fashion->id, 1, 'men clothing,shirts,jeans');
        $women = $this->createCategory('Women', $fashion->id, 2, 'women dresses,kurti,saree');
        $this->createCategory('Kids', $fashion->id, 3, 'kids wear,baby clothes');
        $this->createCategory('Shoes', $fashion->id, 4, 'sneakers,boots,formal');
        $this->createCategory('Bags', $fashion->id, 5, 'handbags,backpacks,laptop bags');
        $this->createCategory('Watches', $fashion->id, 6, 'smartwatch,analog,digital');
        $this->createCategory('Jewelry', $fashion->id, 7, 'gold,silver,earrings');
        $this->createCategory('Sunglasses', $fashion->id, 8, 'rayban,polarized,aviator');

        // Sub-sub under Men
        $this->createCategory('Shirts', $men->id, 1, 'formal,casual,linen');
        $this->createCategory('T-Shirts', $men->id, 2, 'polo,graphic,oversized');

        // === BOOKS SUBCATEGORIES (4) ===
        $this->createCategory('Fiction', $books->id, 1, 'novels,thriller,romance');
        $this->createCategory('Non-Fiction', $books->id, 2, 'biography,self-help,business');
        $this->createCategory('Academic', $books->id, 3, 'textbooks,engineering,medical');
        $this->createCategory('Comics', $books->id, 4, 'manga,graphic novels,dc');

        // === HOME & LIVING SUBCATEGORIES (6) ===
        $this->createCategory('Furniture', $home->id, 1, 'sofa,bed,table');
        $this->createCategory('Kitchen', $home->id, 2, 'cookware,appliances,utensils');
        $this->createCategory('Bedding', $home->id, 3, 'sheets,pillows,comforters');
        $this->createCategory('Decor', $home->id, 4, 'wall art,lamps,vases');
        $this->createCategory('Bath', $home->id, 5, 'towels,shower,accessories');
        $this->createCategory('Lighting', $home->id, 6, 'ceiling,table,led');

        // === SPORTS SUBCATEGORIES (4) ===
        $this->createCategory('Fitness', $sports->id, 1, 'gym,weights,yoga');
        $this->createCategory('Outdoor', $sports->id, 2, 'camping,hiking,cycling');
        $this->createCategory('Team Sports', $sports->id, 3, 'football,cricket,basketball');
        $this->createCategory('Swimming', $sports->id, 4, 'goggles,swimsuit,gear');

        // === TOYS SUBCATEGORIES (4) ===
        $this->createCategory('Action Figures', $toys->id, 1, 'marvel,dc,star wars');
        $this->createCategory('Educational', $toys->id, 2, 'stem,puzzles,blocks');
        $this->createCategory('Dolls', $toys->id, 3, 'barbie,disney,fashion');
        $this->createCategory('Remote Control', $toys->id, 4, 'cars,drones,helicopters');

        // === TOTAL: 6 Root + 24 Sub + 4 Sub-sub = 34+ Categories ===
    }

    /**
     * Create or get existing category with default values
     */
    private function createCategory($name, $parentId, $sortOrder, $keywords = '')
    {

        return Category::firstOrCreate(
            ['category_name' => $name, 'parent_id' => $parentId],
            [
                'description'      => "$name and related products",
                'meta_title'       => $name,
                'meta_description' => "Shop for $name online at best prices",
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
