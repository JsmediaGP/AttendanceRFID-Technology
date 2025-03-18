<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LectureHall;
use Illuminate\Http\JsonResponse;

class LectureHallController extends Controller
{
    // public function index()
    // {
    //     $halls = LectureHall::all();
    //     return view('admin.lecturehalls.index', compact('halls'));
    // }

    
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:lecture_halls',
    //     ]);

    //     try {
    //         LectureHall::create($request->all());
    //         return redirect()->back()->with('success', 'Lecture Hall added successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to add Lecture Hall.');
    //     }
    // }

    // public function update(Request $request, LectureHall $lectureHall)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:lecture_halls,name,' . $lectureHall->id,
    //     ]);

    //     try {
    //         $lectureHall->update($request->all());
    //         return redirect()->back()->with('success', 'Lecture Hall updated successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to update Lecture Hall.');
    //     }
    // }

    // public function destroy(LectureHall $lectureHall)
    // {

    //     try {
    //         $lectureHall->delete();
    //         return response()->json(['success' => true, 'message' => 'Lecture hall deleted successfully.']);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Failed to delete hall.', 'error' => $e->getMessage()], 500);
    //     }
        
    // }

    public function index()
    {
        $lectureHalls = LectureHall::all();
        return view('admin.lecturehalls.index', compact('lectureHalls'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:lecture_halls,name|max:255',
        ]);

        LectureHall::create(['name' => $request->name]);

        return response()->json(['success' => true, 'message' => 'Lecture Hall added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:lecture_halls,name,' . $id . '|max:255',
        ]);

        $hall = LectureHall::findOrFail($id);
        $hall->update(['name' => $request->name]);

        return response()->json(['success' => true, 'message' => 'Lecture Hall updated successfully.']);
    }

    public function destroy($id)
    {
        LectureHall::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Lecture Hall deleted successfully.']);
    }
}
