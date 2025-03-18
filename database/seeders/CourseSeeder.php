<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lecturer1 = User::where('role', 'lecturer')->first();
        $lecturer2 = User::where('role', 'lecturer')->skip(1)->first();

        Course::create([
            'course_code' => 'CSC101',
            'name' => 'Introduction to Computer Science',
            'lecturer_id' => $lecturer1->id ?? 1,
        ]);

        Course::create([
            'course_code' => 'CSC202',
            'name' => 'Data Structures',
            'lecturer_id' => $lecturer2->id ?? 1,
        ]);
    }
}
