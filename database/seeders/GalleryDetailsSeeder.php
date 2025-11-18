<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GalleryDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all gallery IDs from gallery table
        $galleryIds = DB::table('gallery')->pluck('id')->toArray();

        // Raw data WITHOUT gallery_id
        $raw = [
            ['image'=>'pro_1732366392_3698c8c9083a060408a1.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366392_5dfae867c3d9d05d147a.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366393_639dd523c21c997b82d8.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366394_fc36ee0240dfbb97c90e.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366395_604eeb7f6e105cd665a2.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366396_db4148c63de4bd8a5f60.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366397_ff8930b3ef3c04300467.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366397_1674b16070b6cb05609e.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1732366398_e4ecd36e90cbce6d2971.jpg','alt_name'=>null,'sort_order'=>0],

            ['image'=>'pro_1735641684_27e25ca49f6f4df11fe6.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1735641685_9860bb0d60ce9d85ff96.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1735641685_1aa9f5cc533dae11bd0e.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1735641686_8c45b92bb3dd4bd5e22d.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1735641687_7084900e66d02cda189e.jpg','alt_name'=>null,'sort_order'=>0],

            ['image'=>'pro_1735730226_e0e31380734102a19f30.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1735730227_e9681fbef5f87212b30c.jpg','alt_name'=>null,'sort_order'=>0],
            ['image'=>'pro_1735730228_a07b69e52a0c47dbeeb9.jpg','alt_name'=>null,'sort_order'=>0],
        ];

        // Insert with valid dynamic gallery_id
        $data = [];

        foreach ($raw as $item) {
            $data[] = [
                'gallery_id' => $galleryIds[array_rand($galleryIds)], // assign random valid ID
                'image'      => $item['image'],
                'alt_name'   => $item['alt_name'],
                'sort_order' => $item['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('gallery_details')->insert($data);
    }
}