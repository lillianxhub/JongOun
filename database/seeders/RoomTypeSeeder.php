<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {

        RoomType::query()->delete();
        DB::statement('ALTER TABLE room_types AUTO_INCREMENT = 1');

        RoomType::create([
            'name' => 'Regular',
            'detail' => 'A medium-sized room designed for full band rehearsals.',
            'max_capacity' => 7,
            'image' => 'regular.jpg',
        ]);

        RoomType::create([
            'name' => 'Studio',
            'detail' => 'A large room suitable for recording sessions or band practice.',
            'max_capacity' => 10,
            'image' => 'studio.jpg',
        ]);

        RoomType::create([
            'name' => 'Live Session',
            'detail' => 'An extra-large room built for live session recordings and full run-through rehearsals.',
            'max_capacity' => 30,
            'image' => 'live_session.jpg',
        ]);
    }
}
