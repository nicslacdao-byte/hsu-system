<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate - Ensure these match your HTML form names exactly
        $validated = $request->validate([
            'email' => 'required|email',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string', // Nullable is important here
            'birthday' => 'required|date',
            'address' => 'required|string',
            'contact_number' => 'required|string',
            'college' => 'required|string',
            'course' => 'required|string',
        ]);

        // 2. Save or Update the Profile linked to the logged-in User
        StudentProfile::updateOrCreate(
            ['user_id' => Auth::id()], // Search condition (Current User)
            $validated                 // Fields to update
        );

        // 3. Return Success
        return response()->json([
            'success' => true,
            'message' => 'Profile Saved Successfully!'
        ]);
    }
}
