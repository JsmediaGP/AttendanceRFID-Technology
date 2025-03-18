<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function markAttendance($rfid, $hall)
    {
          $now = Carbon::now();  

        // Get the lecture hall ID from the hall name
        $hallId = DB::table('lecture_halls')->where('name', $hall)->value('id');

        if (!$hallId) {
            return response()->json(['error' => 'Invalid Lecture Hall'], 400);
        }

        // Check if RFID belongs to a student  
        $student = DB::table('users')->where('rfid', $rfid)->where('role', 'student')->first();  
        if (!$student) {  
            return response()->json(['error' => 'Invalid RFID'], 400);  
        }  

        // Check if there's a class scheduled for the given hall at the current time  
        $class = DB::table('class_schedules')  
            ->where('lecture_hall_id', $hallId)  
            ->where('day', $now->format('l'))  
            ->whereTime('start_time', '<=', $now->format('H:i:s'))  
            ->whereTime('end_time', '>=', $now->format('H:i:s'))  
            ->first();  

        if (!$class) {   
            return response()->json( 'No class scheduled in this hall at the current time.', 400);  
        }
 
        // Check if class status is 'holding'
        if ($class->status !== 'holding') {
            return response()->json('Class is not holding today. Attendance cannot be marked.', 400);
        }

        // Check if the student already marked attendance for this class today
        $existingAttendance = DB::table('attendance_records')
            ->where('student_id', $student->id)
            ->where('class_id', $class->id)
            ->whereDate('timestamp', $now->toDateString()) // Check for the same day
            ->exists();

        if ($existingAttendance) {
            return response()->json( $student->name . ' ' .$rfid.' Attendance already marked for today.', 200);
        }

        // Mark attendance  
        DB::table('attendance_records')->insert([  
            'student_id' => $student->id,  
            'class_id' => $class->id,  
            'timestamp' => $now,  
        ]);  

        // Get course details  
        $course = DB::table('courses')->where('id', $class->course_id)->first();  

        return response()->json(
             'Attendance marked successfully for '.$student->name.' (RFID: '.$rfid.') in '.$hall.' for course '.$course->course_code.' - '.$course->name.'.'
        , 200);

    }

}
