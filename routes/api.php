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
   // Validate fields
    $request->validate([
        'uid' => 'required|string',
        'hall_name' => 'required|string',
        'image' => 'required|file|mimes:jpg,jpeg,png|max:2048'
    ]);

    // Check if the UID exists in the users table
    $user = User::where('rfid_uid', $request->input('uid'))->first();

    if ($user) {
        // A user with this UID exists. Save the image with the UID as the name.
        $imageName = $user->rfid_uid . '.' . $request->file('image')->getClientOriginalExtension();
        $path = $request->file('image')->storeAs('rfid_images', $imageName, 'public');

        // You can optionally link this image to the user's profile picture field.
        // This example updates the user's profile_picture field to point to the new image.
        $user->profile_picture = 'rfid_images/' . $imageName;
        $user->save();

        // Return a success response with the new path
        return response()->json([
            'uid' => $user->rfid_uid,
            'hall' => $request->input('hall_name'),
            'image_path' => Storage::url($path),
            'status' => 'received and saved'
        ]);

    } else {
        // User not found. Return an error.
        return response()->json([
            'status' => 'error',
            'message' => 'UID not found in the database.'
        ], 404);
    }
});