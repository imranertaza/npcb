<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $players = [
            [
                'name'   => 'Lionel Messi',
                'sport'  => 'Football',
                'position' => 'Forward',
                'team'   => 'Inter Miami',
                'image'  => 'https://placehold.co/200x200?text=Messi',
                'age'    => 38,
                'status' => 1,
            ],
            [
                'name'   => 'Shakib Al Hasan',
                'sport'  => 'Cricket',
                'position' => 'All-rounder',
                'team'   => 'Bangladesh National Team',
                'image'  => 'https://placehold.co/200x200?text=Shakib',
                'age'    => 38,
                'status' => 1,
            ],
            [
                'name'   => 'PV Sindhu',
                'sport'  => 'Badminton',
                'position' => 'Singles',
                'team'   => 'India',
                'image'  => 'https://placehold.co/200x200?text=Sindhu',
                'age'    => 30,
                'status' => 1,
            ],
        ];

        foreach ($players as $player) {
            Player::firstOrCreate(
                ['name' => $player['name'], 'sport' => $player['sport']],
                $player
            );
        }
    }
}
