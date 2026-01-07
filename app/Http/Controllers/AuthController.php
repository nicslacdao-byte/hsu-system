<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 1. Show Login View
    function login(){
        // ERROR NOTE: Ensure you have a file at resources/views/auth/login.blade.php
        // If your file is just 'login.blade.php' in views, change this to: return view('login');
        if (view()->exists('auth.login')) {
            return view('auth.login');
        }
        return view('login'); // Fallback if it's not in the auth folder
    }

    // 2. Handle Login Submission
    function loginPost(Request $request){
        $request->validate([
            'email' => 'required', // Allows '0000'
            'password' => 'required'
        ]);

        // Attempt login
        if(Auth::attempt($request->only('email', 'password'))){
            return redirect()->intended(route('dashboard'));
        }

        return redirect(route('login'))->with("error", "Login details are not valid");
    }

    // 3. Show Register View
    function register(){
        if (view()->exists('auth.register')) {
            return view('auth.register');
        }
        return view('register');
    }

    // 4. Handle Register Submission
    function registerPost(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student' // Forces Student Role
        ]);

        if($user){
            Auth::login($user);
            return redirect()->intended(route('dashboard'));
        }

        return redirect(route('register'))->with("error", "Registration failed");
    }

    // 5. Dashboard Redirector (The Traffic Cop)
    public function dashboard()
    {
        $user = Auth::user();

        // Redirect Admin to Admin Controller
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Show normal dashboard for others
        // ERROR NOTE: Ensure resources/views/dashboard.blade.php exists
        return view('dashboard');
    }

    // 6. Logout
    function logout(){
        Auth::logout();
        return redirect(route('login'));
    }
}
