<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Exception;




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

        try{
            
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'role' => 'required|in:admin,lecturer,student',
            ];

            // Conditional validation for students
            if ($request->role === 'student') {
                $rules['rfid'] = 'required|string|unique:users,rfid';
                $rules['matric_number'] = 'required|string|unique:users,matric_number';
                $rules['profile_picture'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }

            // Validate the request
            $request->validate($rules);

            $profilePicturePath = null;

            // Save profile picture only if it's a student and a file was uploaded
            if ($request->role === 'student' && $request->hasFile('profile_picture')) {
                $extension = $request->file('profile_picture')->getClientOriginalExtension();
                $filename = "{$request->rfid}.{$extension}";
                
                $path = $request->file('profile_picture')->storeAs('profile_pictures', $filename, 'public');
                $profilePicturePath = Storage::url($path);
            }

            // Create the user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'rfid' => $request->role === 'student' ? $request->rfid : null,
                'matric_number' => $request->role === 'student' ? $request->matric_number : null,
                'profile_picture' => $profilePicturePath,
                'password' => Hash::make('password'),
            ]);
             return response()->json([
            'success' => true,
            'message' => 'User registered successfully!',
            'redirect_url' => route('admin.users.index') // Provide the redirect URL here
        ]);

    }catch (Exception $e) {
        // ... (your error handling remains the same) ...
        return response()->json([
            'success' => false,
            'message' => 'An error occurred. Please check your inputs.'
        ], 500);
    

            // return redirect()->route('admin.users.index')->with('success', 'User registered successfully!');

    }
}  
   
    
    public function update(Request $request, User $user)
{
    try {
        // Define base validation rules for all users
        $rules = [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'nullable|in:admin,lecturer,student',
        ];

        // Conditionally add validation rules for students
        if ($request->input('role') === 'student' || $user->role === 'student') {
            $rules['rfid'] = [
                'nullable',
                'string',
                Rule::unique('users')->ignore($user->id),
            ];
            $rules['matric_number'] = [
                'nullable',
                'string',
                Rule::unique('users')->ignore($user->id),
            ];
            $rules['profile_picture'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        // Validate the request data
        $request->validate($rules);

        // Prepare data for the update by only including fields that are present in the request
        $updateData = [];

        if ($request->has('name')) {
            $updateData['name'] = $request->input('name');
        }

        if ($request->has('email')) {
            $updateData['email'] = $request->input('email');
        }

        if ($request->has('role')) {
            $updateData['role'] = $request->input('role');
        }

        if ($request->has('rfid')) {
            $updateData['rfid'] = $request->input('rfid');
        }

        if ($request->has('matric_number')) {
            $updateData['matric_number'] = $request->input('matric_number');
        }

        // Handle profile picture update if a new one is provided
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($user->profile_picture) {
                // Convert the public URL to the internal storage path
                $oldPath = str_replace('/storage/', 'public/', $user->profile_picture);
                Storage::delete($oldPath);
            }
            
            // Save the new profile picture
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $filename = ($request->has('rfid') ? $request->input('rfid') : $user->rfid) . ".{$extension}";
            $path = $request->file('profile_picture')->storeAs('profile_pictures', $filename, 'public');
            $updateData['profile_picture'] = Storage::url($path);
        }

        // Update the user with the prepared data
        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully! 🎉',
            'user' => $user,
        ]);
        
    } catch (QueryException $e) {
        // Catches database-related errors (e.g., duplicate unique fields)
        return response()->json([
            'success' => false,
            'message' => 'Database error: A user with this RFID, Matric Number, or Email may already exist.',
            'error_details' => $e->getMessage()
        ], 409); // 409 Conflict
        
    } catch (Exception $e) {
        // Catches any other unexpected errors
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred during the update.',
            'error_details' => $e->getMessage()
        ], 500); // 500 Internal Server Error
    }
}


    // Delete user old
    // public function destroy($id)
    // {
    //     User::findOrFail($id)->delete();
    //     return response()->json(['message' => 'User deleted successfully!']);
    // }

    public function destroy($id)
    {
        // Find the user by their ID. If not found, a 404 error is returned.
        $user = User::findOrFail($id);
        
        // Check if the user has a profile picture
        if ($user->profile_picture) {
            // Get the path to the picture on the disk and delete it
            // The str_replace is necessary to convert the URL path to a disk path
            Storage::delete(str_replace('/storage/', 'public/', $user->profile_picture));
        }
        
        // Delete the user record from the database
        $user->delete();
        
        return response()->json(['message' => 'User and associated profile picture deleted successfully!']);
    }







    public function showRegistrationForm(Request $request)
    {
        return view('register');
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
    //     $request->validate([
    //         'rfid' => 'required|unique:users,rfid',
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         'role' => 'required|in:admin,lecturer,student',
    //     ]);

    //     // Create the user
    //     DB::table('users')->insert([
    //         'rfid' => $request->rfid,
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'role' => $request->role,
    //         'password' => Hash::make('password'), // Set a default password
    //     ]);

    //     return redirect()->route('home')->with('success', 'User registered successfully!');
    // }

    // Base validation rules for all roles
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|in:admin,lecturer,student',
        ];

        // Conditional validation for students
        if ($request->role === 'student') {
            $rules['rfid'] = 'required|string|unique:users,rfid';
            $rules['matric_number'] = 'required|string|unique:users,matric_number';
            $rules['profile_picture'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        // Validate the request
        $request->validate($rules);

        $profilePicturePath = null;

        // Save profile picture only if it's a student and a file was uploaded
        if ($request->role === 'student' && $request->hasFile('profile_picture')) {
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $filename = "{$request->rfid}.{$extension}";
            
            $path = $request->file('profile_picture')->storeAs('public/profile_pictures', $filename);
            $profilePicturePath = Storage::url($path);
        }

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'rfid' => $request->role === 'student' ? $request->rfid : null,
            'matric_number' => $request->role === 'student' ? $request->matric_number : null,
            'profile_picture' => $profilePicturePath,
            'password' => Hash::make('password'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User registered successfully!');
    }
}
