<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('lecturer')->get();
        $lecturers = User::where('role', 'lecturer')->get(); // Fetch lecturers only
        return view('admin.courses', compact('courses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:10|unique:courses,course_code',
            'name' => 'required|string|max:255',
            'lecturer_id' => 'required|exists:users,id'
        ]);
    
        try {
            $course = Course::create($request->all());
    
            return response()->json(['success' => true, 'message' => 'Course added successfully!', 'course' => $course]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to add course.']);
        }
    }
    
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_code' => 'required|string|max:10|unique:courses,course_code,' . $course->id,
            'name' => 'required|string|max:255',
            'lecturer_id' => 'required|exists:users,id'
        ]);
    
        try {
            $course->update($request->all());
    
            return response()->json(['success' => true, 'message' => 'Course updated successfully!', 'course' => $course]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update course.']);
        }
    }
    
    public function destroy(Course $course)
    {
        try {
            $course->delete();
    
            return response()->json(['success' => true, 'message' => 'Course deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete course.']);
        }
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'course_code' => 'required|string|max:10|unique:courses',
    //         'name' => 'required|string|max:255',
    //         'lecturer_id' => 'required|exists:users,id'
    //     ]);

    //     $course = Course::create($request->all());

    //     return response()->json(['success' => true, 'message' => 'Course added!', 'course' => $course]);
    // }

    // public function update(Request $request, Course $course)
    // {
    //     $request->validate([
    //         'course_code' => 'required|string|max:10|unique:courses,course_code,' . $course->id,
    //         'name' => 'required|string|max:255',
    //         'lecturer_id' => 'required|exists:users,id'
    //     ]);

    //     $course->update($request->all());

    //     return response()->json(['success' => true, 'message' => 'Course updated successfully!', 'course' => $course]);
    // }

    // public function destroy(Course $course)
    // {
    //     $course->delete();
    //     return response()->json(['success' => true, 'message' => 'Course deleted!']);
    // }
}
