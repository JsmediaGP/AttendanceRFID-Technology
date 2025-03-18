<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;



class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role');
        $users = User::when($role, function ($query, $role) {
            return $query->where('role', $role);
        })->get();

        return view('admin.users.index', compact('users'));
    }

    // Store a new user (Onboarding)
    public function store(Request $request)  
    {  
        $request->validate([  
            'name' => 'required|string|max:255',  
            'email' => 'required|email|unique:users,email',  
            'role' => 'required|in:admin,lecturer,student',  
            'rfid' => 'nullable|string|unique:users,rfid' // Optional, must be unique  
        ]);  

        // Create the new user  
        $user = User::create([  
            'name' => $request->name,  
            'email' => $request->email,  
            'role' => $request->role,  
            'rfid' => $request->rfid ?? null, // Only assign if not empty  
            'password' => Hash::make('password123') // Set default hashed password  
        ]);  

        return response()->json([  
            'success' => true,  
            'message' => 'User onboarded successfully!',  
            'user' => $user,  
        ]);  
    }  

   

    public function update(Request $request, User $user)  
    {  
        // Initialize validation rules  
        $rules = [];  
    
        // Conditionally add name validation if it is present in the request  
        if ($request->has('name')) {  
            $rules['name'] = 'string|max:255'; // Not required, but should be a string  
        }  
    
        // Conditionally add email validation if it is present in the request  
        if ($request->has('email')) {  
            $rules['email'] = [  
                'nullable', // Allow to be null (if not provided)  
                'email',  
                Rule::unique('users')->ignore($user->id) // Check uniqueness except for current user  
            ];  
        }  
    
        // Conditionally add role validation if it is present in the request  
        if ($request->has('role')) {  
            $rules['role'] = 'in:admin,lecturer,student'; // Should match one of these values  
        }  
    
        // Conditionally add RFID validation if it is present in the request  
        if ($request->has('rfid')) {  
            $rules['rfid'] = [  
                'nullable',  
                'string',  
                Rule::unique('users')->ignore($user->id) // Check uniqueness, ignore current user  
            ];  
        }  
    
        // Validate input data  
        $request->validate($rules);  
    
        // Update the user's information, using existing values if the new value is not provided  
        $user->update([  
            'name' => $request->name ?? $user->name,  // Retain current name if not provided  
            'email' => $request->email ?? $user->email, // Retain current email if not provided  
            'role' => $request->role ?? $user->role, // Retain current role if not provided  
            'rfid' => $request->rfid ?? $user->rfid, // Retain current RFID if not provided  
        ]);  
    
        return response()->json([  
            'success' => true,  
            'message' => 'User updated successfully!',  
        ]);  
    }    

    // Delete user
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted successfully!']);
    }







    public function showRegistrationForm(Request $request)
    {
        return view('register');
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
        $request->validate([
            'rfid' => 'required|unique:users,rfid',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,lecturer,student',
        ]);

        // Create the user
        DB::table('users')->insert([
            'rfid' => $request->rfid,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make('password'), // Set a default password
        ]);

        return redirect()->route('home')->with('success', 'User registered successfully!');
    }
}
