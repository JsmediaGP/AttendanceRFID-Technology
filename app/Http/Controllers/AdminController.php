<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\LectureHall;
use App\Models\ClassSchedule;
class AdminController extends Controller
{
    public function index()
    {
        // return view('admin.dashboard');
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalCourses = Course::count();
        $totalLectureHalls = LectureHall::count();
        $totalSchedules = ClassSchedule::count();

        return view('admin.dashboard', compact('totalUsers', 'totalCourses', 'totalLectureHalls', 'totalSchedules'));
    }
}
