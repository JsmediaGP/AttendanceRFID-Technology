<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LectureHall;

class LectureHallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LectureHall::create([
            'name' => 'Main Hall',
            // 'capacity' => 200,
        ]);

        LectureHall::create([
            'name' => 'Lecture Room 1',
            // 'capacity' => 100,
        ]);

        LectureHall::create([
            'name' => 'Lecture Room 2',
            // 'capacity' => 80,
        ]);
    }
}
