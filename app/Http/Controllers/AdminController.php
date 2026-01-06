<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StaffSchedule; // <--- IMPORTANT: Don't forget this import!

class AdminController extends Controller
{
    // --- SHOW ADMIN DASHBOARD ---
    public function index()
    {
        // 1. Fetch Staff
        $staffMembers = User::where('role', 'staff')->orderBy('created_at', 'desc')->get();

        // 2. Fetch Students
        $students = User::where('role', 'student')->orderBy('created_at', 'desc')->get();

        // 3. NEW: Fetch Schedules (This was missing causing your error)
        $schedules = StaffSchedule::all();

        // 4. Pass all variables to the view
        return view('admin_dashboard', compact('staffMembers', 'students', 'schedules'));
    }

    // --- CREATE STAFF ACCOUNT ---
    public function createStaff(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'staff'
        ]);

        return back()->with('success', 'Staff account created successfully!');
    }

    // --- DELETE USER ---
    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return back()->with('success', 'User deleted successfully.');
        }
        return back()->withErrors(['msg' => 'User not found.']);
    }

    // --- NEW: UPDATE SCHEDULE ---
    public function updateSchedule(Request $request)
    {
        // This validates and saves the schedule when you click "Save" in the popup
        StaffSchedule::updateOrCreate(
            ['user_id' => $request->user_id, 'day' => $request->day],
            [
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_off' => $request->is_off == 'true' ? 1 : 0
            ]
        );

        return response()->json(['success' => true]);
    }
    // --- UPDATE STAFF NAME ---
    public function updateStaffName(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            $user->name = $request->name; // This requires the 'name' column we just added
            $user->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
}
