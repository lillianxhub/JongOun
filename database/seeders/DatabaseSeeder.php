<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
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

        \App\Models\Room::query()->delete();
        DB::statement('ALTER TABLE rooms AUTO_INCREMENT = 1');
        \App\Models\Room::create([
            'name' => 'A01',
            'room_type_id' => 1,
            'capacity' => 5,
            'status' => 'available',
            'price' => 150.00,
        ]);
        \App\Models\Room::create([
            'name' => 'B01',
            'room_type_id' => 2,
            'capacity' => 8,
            'status' => 'available',
            'price' => 250.00,
        ]);
        \App\Models\Room::create([
            'name' => 'C01',
            'room_type_id' => 3,
            'capacity' => 25,
            'status' => 'available',
            'price' => 500.00,
        ]);

        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.co',
            'role' => 'admin',
            'password' => bcrypt('admin123'), // Change this to a secure password
        ]);

        \App\Models\Instrument::create([
            'name' => 'microphone',
            'stock' => 99,
            'price' => 20.00,
        ]);

        \App\Models\Instrument::create([
            'name' => 'cable',
            'stock' => 99,
            'price' => 0.00,
        ]);



        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
