<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; 
use Carbon\Carbon;

class RFIDController extends Controller
{
    protected $latestRfid; 
    protected $rfidFilePath;  

    public function __construct()  
    {  
        $this->rfidFilePath = storage_path('app/RFID.txt'); // Path to the RFID.txt file  
    }  
    public function scanRFID(Request $request) {
        
        $request->validate([  
            'rfid' => 'required|string',  
            'hall_name' => 'required|string',  
        ]);  

        $rfid = $request->input('rfid');  
        $hallName = $request->input('hall_name');  
       

        // Check if the user exists  
        $user = User::where('rfid', $rfid)->first();  
        
        if ($user) {  
            // If user exists, mark attendance  
            $attendanceController = new AttendanceController();  
            return $attendanceController->markAttendance($rfid, $hallName);  
        } else {  
            // User not found; write the RFID to the file  
            file_put_contents($this->rfidFilePath, $rfid);  
            return response()->json( 
                // 'rfid' => $rfid,  
                'User not found, Contact Admin.',  
                // 'success' => false,  
             404);  
        } 
    } 


    public function getLatestRfid()  
    {  

        // Read the latest RFID from the text file  
        if (file_exists($this->rfidFilePath)) {  
            $latestRfid = file_get_contents($this->rfidFilePath);  
            return response()->json(['rfid' => trim($latestRfid)]); // Trim to remove whitespace/newlines  
        }  

        return response()->json(['rfid' => null]);
    }  

   
   
    
}
 