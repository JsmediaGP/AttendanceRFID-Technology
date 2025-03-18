<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\LectureHall;
use App\Models\User;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course1 = Course::first();
        $course2 = Course::skip(1)->first();
        $lecturer1 = User::where('role', 'lecturer')->first();
        $lecturer2 = User::where('role', 'lecturer')->skip(1)->first();
        $hall1 = LectureHall::first();
        $hall2 = LectureHall::skip(1)->first();

        ClassSchedule::create([
            'course_id' => $course1->id ?? 1,
            // 'lecturer_id' => $lecturer1->id ?? 1,
            'lecture_hall_id' => $hall1->id ?? 1,
            'day' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
        ]);

        ClassSchedule::create([
            'course_id' => $course2->id ?? 1,
            // 'lecturer_id' => $lecturer2->id ?? 1,
            'lecture_hall_id' => $hall2->id ?? 1,
            'day' => 'Wednesday',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
        ]);
    }
}
