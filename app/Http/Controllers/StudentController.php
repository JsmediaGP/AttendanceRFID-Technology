<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard(Request $request)  
    {  
         
        $user = Auth::user();  

        // Fetch all courses the student is enrolled in  
        $courses = $user->courses; // Assuming there's a relationship defined on User model  

        // Start with all attendance records for the logged-in student  
        $attendanceRecords = AttendanceRecord::where('student_id', $user->id);  

        // Filtering by course if a course_id is provided  
        if ($request->has('course_id') && $request->input('course_id') != '') {  
            $attendanceRecords = $attendanceRecords->whereHas('classSchedule', function($query) use ($request) {  
                $query->where('course_id', $request->input('course_id'));  // Adjust field names according to your schema  
            });  
        }  

        // Filtering by date if a date is provided  
        if ($request->has('date') && $request->input('date') != '') {  
            $attendanceRecords = $attendanceRecords->whereDate('date', $request->input('date'));  
        }  

        // Get paginated attendance records  
        $attendanceRecords = $attendanceRecords->paginate(10); // Adjust the limit if necessary  

        return view('student.dashboard', compact('courses', 'attendanceRecords'));  
    }  
}
