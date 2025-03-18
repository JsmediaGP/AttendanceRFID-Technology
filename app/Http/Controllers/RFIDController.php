<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RFIDController extends Controller
{
    // public function storeRFID(Request $request)
    // {
    //     Cache::put('latest_rfid', $request->rfid_tag, now()->addSeconds(10)); // Store for 10 seconds
    //     return response()->json(['message' => 'RFID received']);
    // }

    public function getLatestRFID()
    {
        return response()->json(['rfid' => Cache::get('latest_rfid', '')]);
    }
    public function storeRFID(Request $request)
    {
        Log::info('Received RFID:', $request->all());
        return response()->json(['success' => true, 'message' => 'RFID stored successfully']);
    }

    
}
