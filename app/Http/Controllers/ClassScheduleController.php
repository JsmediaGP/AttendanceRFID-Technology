<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\LectureHall;

class ClassScheduleController extends Controller
{
    public function index(Request $request)  
    {  
        $courses = Course::all();  
        $lectureHalls = LectureHall::all();  
    
        $query = ClassSchedule::query()->with('course.lecturer', 'lectureHall');  
    
        if ($request->course_id) {  
            $query->where('course_id', $request->course_id);  
        }  
        if ($request->lecturer_id) {  
            $query->whereHas('course', function ($q) use ($request) {  
                $q->where('lecturer_id', $request->lecturer_id);  
            });  
        }  
        if ($request->day) {  
            $query->where('day', $request->day);  
        }  
        // Add filter for lecture hall  
        if ($request->lecture_hall_id) {  
            $query->where('lecture_hall_id', $request->lecture_hall_id);  
        }  
    
        $schedules = $query->get();  
    
        return view('admin.class_schedules.index', compact('schedules', 'courses', 'lectureHalls'));  
    }  

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'course_id' => 'required|exists:courses,id',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //         'day' => 'required',
    //         'lecture_hall_id' => 'required|exists:lecture_halls,id',
    //     ]);

    //     ClassSchedule::create($request->all());

    //     return response()->json(['success' => true, 'message' => 'Class Schedule added successfully.']);
    // }
    public function store(Request $request)  
{  
    $request->validate([  
        'course_id' => 'required|exists:courses,id',  
        'start_time' => 'required|date_format:H:i',  
        'end_time' => 'required|date_format:H:i|after:start_time',  
        'day' => 'required',  
        'lecture_hall_id' => 'required|exists:lecture_halls,id',  
    ]);  

    // Check for existing schedules on the same day and time  
    $existingSchedule = ClassSchedule::where('lecture_hall_id', $request->lecture_hall_id)  
        ->where('day', $request->day)  
        ->where(function ($query) use ($request) {  
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])  
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])  
                  ->orWhere(function ($query) use ($request) {  
                      $query->where('start_time', '<=', $request->start_time)  
                            ->where('end_time', '>=', $request->end_time);  
                  });  
        })  
        ->exists();  

    if ($existingSchedule) {  
        return response()->json(['success' => false, 'message' => 'The lecture hall is already booked for this time on the selected day.'], 400);  
    }  

    ClassSchedule::create($request->all());  

    return response()->json(['success' => true, 'message' => 'Class Schedule added successfully.']);  
}  
//     public function update(Request $request, $id)  
// {  
//     $request->validate([  
//         'course_id' => 'nullable|exists:courses,id',  
//         'start_time' => 'nullable',  
//         'end_time' => 'nullable',  
//         'day' => 'nullable',  
//         'lecture_hall_id' => 'nullable|exists:lecture_halls,id',  
//     ]);  

//     try {  
//         $schedule = ClassSchedule::findOrFail($id);  
//         $schedule->update($request->all());  
//         return response()->json(['success' => true, 'message' => 'Class Schedule updated successfully.']);  
//     } catch (\Exception $e) {  
//         return response()->json(['success' => false, 'message' => 'Failed to update schedule: ' . $e->getMessage()], 500);  
//     }  
// }  
public function update(Request $request, $id)  
{  
    $request->validate([  
        'course_id' => 'nullable|exists:courses,id',  
        'start_time' => 'nullable|date_format:H:i',  
        'end_time' => 'nullable|date_format:H:i|after:start_time',  
        'day' => 'nullable',  
        'lecture_hall_id' => 'nullable|exists:lecture_halls,id',  
    ]);  

    $schedule = ClassSchedule::findOrFail($id);  

    // Check for existing schedules on the same day and time except for the current schedule  
    $existingSchedule = ClassSchedule::where('lecture_hall_id', $request->lecture_hall_id)  
        ->where('day', $request->day)  
        ->where(function ($query) use ($request, $schedule) {  
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])  
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])  
                  ->orWhere(function ($query) use ($request, $schedule) {  
                      $query->where('start_time', '<=', $request->start_time)  
                            ->where('end_time', '>=', $request->end_time);  
                  })  
                  ->where('id', '!=', $schedule->id); // Exclude the current schedule  
        })  
        ->exists();  

    if ($existingSchedule) {  
        return response()->json(['success' => false, 'message' => 'The lecture hall is already booked for this time on the selected day.'], 400);  
    }  

    $schedule->update($request->all());  

    return response()->json(['success' => true, 'message' => 'Class Schedule updated successfully.']);  
}  

public function destroy($id)  
{  
    try {  
        $schedule = ClassSchedule::findOrFail($id);  
        $schedule->delete();  
        return response()->json(['success' => true, 'message' => 'Class Schedule deleted successfully.']);  
    } catch (\Exception $e) {  
        return response()->json(['success' => false, 'message' => 'Failed to delete schedule: ' . $e->getMessage()], 500);  
    }  
}  



}
