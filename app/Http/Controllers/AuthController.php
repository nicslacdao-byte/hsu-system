<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- REGISTER LOGIC ---
    public function register(Request $request)
    {
        // 1. Validate inputs
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6', // 'confirmed' checks password_confirmation field
            'role' => 'required'
        ]);

        // 2. Create the User in Database
        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        // 3. NO Auto-Login. Redirect to Login Page with Success Message.
        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    // --- LOGIN LOGIC ---
    public function login(Request $request)
    {
        // 1. Validate inputs
        // IMPORTANT FIX: Removed '|email' rule so "0000" is accepted as a string
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
            'role' => 'required' // We must receive the role (student/staff/admin) from the form
        ]);

        // 2. Attempt Login with STRICT ROLE CHECK
        // We only verify email & password first
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // --- STRICT SEPARATION LOGIC ---
            // If the Database Role doesn't match the Tab they clicked...
            if ($user->role !== $request->role) {

                // Kick them out immediately
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Access Denied: You cannot log in here. Please use the correct portal (Student/Staff/Admin).',
                ]);
            }

            // 3. Redirect Based on Role
            if ($user->role === 'admin') {
                return redirect()->intended('/'); // Routes will send this to AdminController
            } elseif ($user->role === 'staff') {
                return redirect()->intended('/'); // Routes will send this to StaffController
            }

            return redirect()->intended('/'); // Routes will send this to Student Dashboard
        }

        // 4. Failed?
        return back()->withErrors([
            'email' => 'Invalid credentials or incorrect user type selected.',
        ]);
    }

    // --- LOGOUT LOGIC ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
