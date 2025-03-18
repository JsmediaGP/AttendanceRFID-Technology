<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RFIDController;
use Illuminate\Support\Facades\Log;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/rfid-scan', [RFIDController::class, 'scanRFID']); 
// Route to get the latest RFID  
Route::get('/latest-rfid', [RFIDController::class, 'getLatestRfid']); 