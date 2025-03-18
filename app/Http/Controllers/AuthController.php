<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'lecturer') {
        return redirect()->route('lecturer.dashboard');
    }

    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    // If role is not recognized, log out and redirect to login
    Auth::logout();
    return redirect()->route('login')->with('error', 'Invalid role assigned.');
}




    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
