<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show Login Page
    function login(){
        return view('auth.login');
    }

    // Show Register Page
    function register(){
        return view('auth.register');
    }

    // Handle Login Logic (Updated for Admin 0000)
    function loginPost(Request $request){
        $request->validate([
            'email' => 'required', // Removed '|email' so '0000' is allowed
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            return redirect()->intended(route('dashboard'));
        }

        return redirect(route('login'))->with("error", "Login details are not valid");
    }

    // Handle Registration Logic (Updated to force 'student' role)
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
            'role' => 'student' // <--- Added this to force Student role
        ]);

        if($user){
            Auth::login($user);
            return redirect()->intended(route('dashboard'));
        }

        return redirect(route('register'))->with("error", "Registration failed");
    }

    // Dashboard Redirection (Updated to handle Admin vs Student)
    public function dashboard()
    {
        // Get the currently logged in user
        $user = Auth::user();

        // If the user is an ADMIN, send them to the Admin Panel
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // If the user is a STUDENT/STAFF, show the normal dashboard
        return view('dashboard');
    }

    // Handle Logout
    function logout(){
        Auth::logout(); // Use the Facade directly
        return redirect(route('login'));
    }
}
