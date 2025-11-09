<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Root categories
        $electronics = Category::firstOrCreate(
            ['category_name' => 'Electronics'],
            [
                'description' => 'All electronic items and gadgets',
                'meta_title' => 'Electronics',
                'meta_description' => 'Shop for electronics, gadgets, and devices',
                'meta_keyword' => 'electronics,gadgets,devices',
                'header_menu' => '1',
                'side_menu' => '1',
                'sort_order' => 1,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        $fashion = Category::firstOrCreate(
            ['category_name' => 'Fashion'],
            [
                'description' => 'Clothing, shoes, and accessories',
                'meta_title' => 'Fashion',
                'meta_description' => 'Latest fashion trends and styles',
                'meta_keyword' => 'clothing,shoes,accessories',
                'header_menu' => '1',
                'side_menu' => '1',
                'sort_order' => 2,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        $books = Category::firstOrCreate(
            ['category_name' => 'Books'],
            [
                'description' => 'Books and literature',
                'meta_title' => 'Books',
                'meta_description' => 'Wide range of books and novels',
                'meta_keyword' => 'books,novels,literature',
                'header_menu' => '0',
                'side_menu' => '1',
                'sort_order' => 3,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        // Subcategories under Electronics
        $mobiles = Category::firstOrCreate(
            ['category_name' => 'Mobile Phones', 'parent_id' => $electronics->id],
            [
                'description' => 'Smartphones and mobile devices',
                'meta_title' => 'Mobile Phones',
                'meta_description' => 'Latest smartphones and mobile devices',
                'meta_keyword' => 'smartphones,mobile,phones',
                'sort_order' => 1,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        $laptops = Category::firstOrCreate(
            ['category_name' => 'Laptops', 'parent_id' => $electronics->id],
            [
                'description' => 'Personal and business laptops',
                'meta_title' => 'Laptops',
                'meta_description' => 'Wide range of laptops',
                'meta_keyword' => 'laptops,computers,notebooks',
                'sort_order' => 2,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        // Sub-subcategories under Mobile Phones
        Category::firstOrCreate(
            ['category_name' => 'Android Phones', 'parent_id' => $mobiles->id],
            [
                'description' => 'Phones running Android OS',
                'meta_title' => 'Android Phones',
                'meta_description' => 'Latest Android smartphones',
                'meta_keyword' => 'android,smartphones',
                'sort_order' => 1,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        Category::firstOrCreate(
            ['category_name' => 'iPhones', 'parent_id' => $mobiles->id],
            [
                'description' => 'Apple iPhones',
                'meta_title' => 'iPhones',
                'meta_description' => 'Latest Apple iPhones',
                'meta_keyword' => 'apple,iphone',
                'sort_order' => 2,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        // Subcategories under Fashion
        $men = Category::firstOrCreate(
            ['category_name' => 'Men', 'parent_id' => $fashion->id],
            [
                'description' => 'Men clothing and accessories',
                'meta_title' => 'Men Fashion',
                'meta_description' => 'Latest men fashion trends',
                'meta_keyword' => 'men,clothing,accessories',
                'sort_order' => 1,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        $women = Category::firstOrCreate(
            ['category_name' => 'Women', 'parent_id' => $fashion->id],
            [
                'description' => 'Women clothing and accessories',
                'meta_title' => 'Women Fashion',
                'meta_description' => 'Latest women fashion trends',
                'meta_keyword' => 'women,clothing,accessories',
                'sort_order' => 2,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        // Sub-subcategories under Women
        Category::firstOrCreate(
            ['category_name' => 'Dresses', 'parent_id' => $women->id],
            [
                'description' => 'Women dresses and gowns',
                'meta_title' => 'Dresses',
                'meta_description' => 'Latest dresses for women',
                'meta_keyword' => 'dresses,gowns,women',
                'sort_order' => 1,
                'status' => '1',
                'createdBy' => 1,
            ]
        );

        Category::firstOrCreate(
            ['category_name' => 'Shoes', 'parent_id' => $women->id],
            [
                'description' => 'Women shoes and footwear',
                'meta_title' => 'Shoes',
                'meta_description' => 'Latest shoes for women',
                'meta_keyword' => 'shoes,footwear,women',
                'sort_order' => 2,
                'status' => '1',
                'createdBy' => 1,
            ]
        );
    }
}
