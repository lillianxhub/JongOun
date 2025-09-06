<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run()
    {
        RoomType::insert([
            ['name' => 'Regular', 'max_capacity' => 6],
            ['name' => 'Studio', 'max_capacity' => 10],
            ['name' => 'Live-Session', 'max_capacity' => 30],
        ]);
    }
}
