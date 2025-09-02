<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\RFIDController;
use App\Models\User;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('/rfid-scan', [RFIDController::class, 'scanRFID']); 
// Route to get the latest RFID  
Route::get('/latest-rfid', [RFIDController::class, 'getLatestRfid']); 


Route::post('/rfid-scan', function (Request $request) {
    // Validate required fields
    $request->validate([
        'uid' => 'required|string',
        'hall_name' => 'required|string',
        'image' => 'required|file|mimes:jpg,jpeg,png|max:2048'
    ]);

    // Check if the UID exists in the users table
    $userExists = User::where('rfid', $request->input('uid'))->exists();

    if ($userExists) {
        // User with this UID exists; save the image
        $uid = $request->input('uid');
        $extension = 'jpg'; // Always save as JPG as per the requirement
        $imageName = "{$uid}.{$extension}";
        
        $path = $request->file('image')->storeAs('attendanceImages', $imageName, 'public');

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance image saved.',
            'uid' => $uid,
            'image_path' => Storage::url($path)
        ]);

    } else {
        // User not found; log the UID to a text file
        $uid = $request->input('uid');
        $logEntry = "UID: {$uid}, Timestamp: " . now() . "\n";
        
        Storage::disk('local')->append('unregistered_uids.txt', $logEntry);

        // Return a JSON error message
        return response()->json([
            'status' => 'error',
            'message' => 'UID not found in the database. UID logged for registration.'
        ], 404);
    }
});