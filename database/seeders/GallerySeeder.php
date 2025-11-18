<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'New test',
                'thumb' => 'pro_1732366572_c98d330e767950f17d4b.jpg',
                'alt_name' => null,
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
            [
                'name' => '231124',
                'thumb' => 'pro_1732366391_bb685d1ec4008df88ae2.jpg',
                'alt_name' => null,
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
            [
                'name' => '111111111',
                'thumb' => 'pro_1735730225_bf9af5840dcf408bd536.jpg',
                'alt_name' => null,
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
            [
                'name' => 'Cjhfgfjyfuedrfd',
                'thumb' => 'pro_1737632758_1bda4b24571726d45020.jpg',
                'alt_name' => null,
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
            [
                'name' => 'ttttttt',
                'thumb' => 'pro_1740981862_66c0d4095ca590ac548d.jpg',
                'alt_name' => null,
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
            [
                'name' => 'Test 3',
                'thumb' => 'pro_1740996418_48515422e7c448123426.jpg',
                'alt_name' => 'Test 3',
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
            [
                'name' => 'test',
                'thumb' => 'pro_1741516940_47dcd9af2626d7ab854e.jpg',
                'alt_name' => 'test',
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
            [
                'name' => 'Cartier Necklace',
                'thumb' => 'pro_1758115909_c8da5fecd14a9902a48c.png',
                'alt_name' => 'Cartier Necklace',
                'sort_order' => 0,
                'createdBy' => 1,
                'updatedBy' => null,
            ],
        ];

        foreach ($data as $item) {
            DB::table('gallery')->insert($item);
        }
    }
}