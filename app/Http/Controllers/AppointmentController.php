<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\DailyLimit;
use App\Models\StaffSchedule;
use App\Models\User;
use App\Models\Announcement; // <--- IMPORTANT: Added this model
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // 1. Fetch appointments & Announcements
    public function index()
    {
        // Fetch Appointments
        $appointments = Appointment::where('user_id', Auth::id())
                        ->orderBy('appointment_date', 'desc')
                        ->get();

        // Fetch Staff & Schedules (For the Schedule Tab)
        $staffMembers = User::where('role', 'staff')->get();
        $schedules = StaffSchedule::all();

        // NEW: Fetch Announcements (For the Dashboard)
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        // FIX: Added 'staffMembers', 'schedules', and 'announcements' to compact()
        return view('main', compact('appointments', 'staffMembers', 'schedules', 'announcements'));
    }

    // 2. Save a new appointment
    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'time_slot' => 'required',
            'appointment_type' => 'required'
        ]);

        $date = Carbon::parse($request->appointment_date);
        $dateString = $date->format('Y-m-d');

        // RULE 1: Disable Weekends
        if ($date->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Appointments are not available on weekends (Saturday and Sunday).'
            ]);
        }

        // RULE 2: DYNAMIC DAILY LIMIT
        $customLimit = DailyLimit::where('date', $dateString)->first();
        $maxLimit = $customLimit ? $customLimit->limit : 50;

        $currentCount = Appointment::where('appointment_date', $dateString)
                        ->whereIn('status', ['Pending', 'Approved'])
                        ->count();

        if ($currentCount >= $maxLimit) {
            return response()->json([
                'success' => false,
                'message' => "This date is fully booked ({$currentCount}/{$maxLimit} slots). Please select another date."
            ]);
        }

        // RULE 3: Prevent Double Booking
        $existing = Appointment::where('user_id', Auth::id())
                    ->whereIn('status', ['Pending', 'Approved'])
                    ->where('appointment_date', '>=', Carbon::today())
                    ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active upcoming appointment.'
            ]);
        }

        Appointment::create([
            'user_id' => Auth::id(),
            'appointment_type' => $request->appointment_type,
            'appointment_date' => $dateString,
            'time_slot' => $request->time_slot,
            'status' => 'Pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment Successfully Booked!'
        ]);
    }

    // 3. Cancel
    public function cancel($id)
    {
        $appointment = Appointment::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if ($appointment) {
            $appointment->status = 'Cancelled';
            $appointment->save();

            return response()->json(['success' => true, 'message' => 'Appointment cancelled.']);
        }

        return response()->json(['success' => false, 'message' => 'Error cancelling.']);
    }
}
