<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user();

        if ($lecturer->role !== 'lecturer') {
            abort(403, 'Unauthorized access');
        }

        // // Fetch all courses for the logged-in lecturer
        // $courses = Course::where('lecturer_id', $lecturer->id)->get();

        // return view('lecturer.dashboard', compact('courses'));
        // $lecturerId = Auth::id();

        // Get only the courses that this lecturer is taking
        $courses = Course::where('lecturer_id', $lecturer->id)->get();

        // Get class schedules related to the lecturer's courses
        $classSchedules = ClassSchedule::whereIn('course_id', $courses->pluck('id'))->get();

        return view('lecturer.dashboard', compact('courses', 'classSchedules'));
    }
    public function updateClassStatus(Request $request, $id)
    {
        // $schedule = ClassSchedule::findOrFail($id);

        // // Ensure the lecturer can only update their own class schedules
        // if ($schedule->course->lecturer_id !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        // $schedule->status = $request->status;
        // $schedule->save();

        // return response()->json(['message' => 'Class status updated successfully']);
        $validated = $request->validate([  
            'status' => 'required|string',  
        ]);  
    
        $schedule = ClassSchedule::findOrFail($id);  
        $schedule->status = $validated['status'];  
        $schedule->save();  
    
        // Return a JSON response back to the client  
        return response()->json([  
            'success' => true,  
            'newStatus' => $validated['status'],  
            'message' => 'Class status updated successfully'  
        ]); 
    }


    // AJAX function to fetch filtered schedules
    public function filterSchedules(Request $request)
    {
        // $lecturer = Auth::user();

        // $query = ClassSchedule::whereHas('course', function ($q) use ($lecturer) {
        //     $q->where('lecturer_id', $lecturer->id);
        // });

        // if ($request->filled('course_id')) {
        //     $query->where('course_id', $request->course_id);
        // }

        // if ($request->filled('date')) {
        //     $query->whereDate('date', $request->date);
        // }

        // $classSchedules = $query->get();

        // return response()->json($classSchedules);
        $lecturerId = Auth::id();

        // Fetch only the courses assigned to the lecturer
        $courses = Course::where('lecturer_id', $lecturerId)->pluck('id');
    
        // Apply filters
        $query = ClassSchedule::whereIn('course_id', $courses);
    
        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }
    
        if ($request->day) {
            $query->where('day', $request->day);
        }
    
        return response()->json($query->with('course')->get());
    }

  
    
    public function viewAttendance(Request $request, $courseId)  
    {  
        $lecturer = Auth::user();  
        $course = Course::findOrFail($courseId);  
    
        // Authorization check  
        if ($course->lecturer_id !== $lecturer->id) {  
            abort(403, 'Unauthorized action.');  
        }  
    
        // Initialize a query for attendance records  
        $query = AttendanceRecord::with(['student', 'classSchedule'])  
            ->whereHas('classSchedule', function ($scheduleQuery) use ($courseId) {  
                $scheduleQuery->where('course_id', $courseId);  
            });  
    
        // Apply filters only if set, else show all records  
        if ($request->has('date') && $request->input('date') != '') {  
            $query->whereDate('timestamp', $request->input('date'));  
        }  
    
        if ($request->has('day') && $request->input('day') != '') {  
            $query->whereHas('classSchedule', function ($scheduleQuery) use ($request) {  
                $scheduleQuery->where('day', $request->input('day'));  
            });  
        }  
    
        if ($request->has('student_name') && $request->input('student_name') != '') {  
            $query->whereHas('student', function ($studentQuery) use ($request) {  
                $studentQuery->where('name', 'like', '%' . $request->input('student_name') . '%');  
            });  
        }  
    
        // Retrieve the result and paginate  
        $attendanceRecords = $query->orderBy('timestamp', 'desc')->paginate(10);  
    
        return view('lecturer.attendance', compact('attendanceRecords', 'course'));  

    }


    public function viewAttendanceSummary(Request $request, $courseId)  
    {  
        $lecturer = Auth::user();  
        $course = Course::findOrFail($courseId);  

        if ($course->lecturer_id !== $lecturer->id) {  
            abort(403, 'Unauthorized action.');  
        }  

        // Fetch attendance counts for each student for the selected course  
        $attendanceCounts = AttendanceRecord::select('student_id', DB::raw('count(*) as count'))  
            ->whereHas('classSchedule', function ($query) use ($courseId) {  
                $query->where('course_id', $courseId);  
            })  
            ->groupBy('student_id')  
            ->with('student') // Eager load students  
            ->get();  

        return view('lecturer.attendance_summary', compact('attendanceCounts', 'course'));  
    }  

    // public function exportAttendanceToCsv($courseId)  
    // {  
    //     $lecturer = Auth::user();  
    //     $course = Course::findOrFail($courseId);  

    //     if ($course->lecturer_id !== $lecturer->id) {  
    //         abort(403, 'Unauthorized action.');  
    //     }  

    //     // Fetch attendance records for the selected course  
    //     $attendanceRecords = AttendanceRecord::with(['student', 'classSchedule'])  
    //         ->whereHas('classSchedule', function ($query) use ($courseId) {  
    //             $query->where('course_id', $courseId);  
    //         })  
    //         ->get();  

    //     // Create CSV response  
    //     $csvFileName = 'attendance_records_' . $course->course_code . '.csv';  
    //     $response = new StreamedResponse(function () use ($attendanceRecords) {  
    //         $handle = fopen('php://output', 'w');  
    //         fputcsv($handle, ['Student Name', 'Class Schedule', 'Date & Time', 'Attendance Count']);  

    //         $attendanceCounts = $attendanceRecords->groupBy('student_id')->map(function ($group) {  
    //             return $group->count();  
    //         });  

    //         foreach ($attendanceRecords as $record) {  
    //             fputcsv($handle, [  
    //                 $record->student->name,  
    //                 $record->classSchedule->day . ' - ' . date('h:i A', strtotime($record->classSchedule->start_time)) . ' to ' . date('h:i A', strtotime($record->classSchedule->end_time)),  
    //                 \Carbon\Carbon::parse($record->timestamp)->format('Y-m-d H:i A'),  
    //                 $attendanceCounts->get($record->student_id, 0)  
    //             ]);  
    //         }  

    //         fclose($handle);  
    //     });  

    //     $response->headers->set('Content-Type', 'text/csv');  
    //     $response->headers->set('Content-Disposition', 'attachment; filename="' . $csvFileName . '"');  

    //     return $response;  
    // }  
    public function exportDetailedAttendanceToCsv($courseId)  
    {  
        $lecturer = Auth::user();  
        $course = Course::findOrFail($courseId);  

        if ($course->lecturer_id !== $lecturer->id) {  
            abort(403, 'Unauthorized action.');  
        }  

        // Fetch all attendance records for the selected course (without filtering for export)  
        $attendanceRecords = AttendanceRecord::with(['student', 'classSchedule'])  
            ->whereHas('classSchedule', function ($query) use ($courseId) {  
                $query->where('course_id', $courseId);  
            })  
            ->orderBy('timestamp', 'desc')  
            ->get();  

        // Create CSV response  
        $csvFileName = 'detailed_attendance_records_' . $course->course_code . '.csv';  
        $response = new StreamedResponse(function () use ($attendanceRecords) {  
            $handle = fopen('php://output', 'w');  
            fputcsv($handle, ['Student Name', 'Class Schedule', 'Date & Time']);  

            foreach ($attendanceRecords as $record) {  
                fputcsv($handle, [  
                    $record->student->name,  
                    $record->classSchedule->day . ' - ' . date('h:i A', strtotime($record->classSchedule->start_time)) . ' to ' . date('h:i A', strtotime($record->classSchedule->end_time)),  
                    \Carbon\Carbon::parse($record->timestamp)->format('Y-m-d H:i A')  
                ]);  
            }  

            fclose($handle);  
        });  

        $response->headers->set('Content-Type', 'text/csv');  
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $csvFileName . '"');  

        return $response;  
    }  

    public function exportAttendanceSummaryToCsv($courseId)  
    {  
        $lecturer = Auth::user();  
        $course = Course::findOrFail($courseId);  

        if ($course->lecturer_id !== $lecturer->id) {  
            abort(403, 'Unauthorized action.');  
        }  

        // Fetch attendance counts for each student for the selected course  
        $attendanceCounts = AttendanceRecord::select('student_id', DB::raw('count(*) as count'))  
            ->whereHas('classSchedule', function ($query) use ($courseId) {  
                $query->where('course_id', $courseId);  
            })  
            ->groupBy('student_id')  
            ->with('student') // Eager load students  
            ->get();  

        // Create CSV response for summary  
        $csvFileName = 'attendance_summary_' . $course->course_code . '.csv';  
        $response = new StreamedResponse(function () use ($attendanceCounts) {  
            $handle = fopen('php://output', 'w');  
            fputcsv($handle, ['Student Name', 'Attendance Count']);  

            foreach ($attendanceCounts as $record) {  
                fputcsv($handle, [  
                    $record->student->name,  
                    $record->count  
                ]);  
            }  

            fclose($handle);  
        });  

        $response->headers->set('Content-Type', 'text/csv');  
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $csvFileName . '"');  

        return $response;  
    }  


}
