<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\Appointment;
use App\Models\DailyLimit;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        // 1. Search Logic
        $query = StudentProfile::with('user');
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lastname', 'LIKE', "%{$search}%")
                  ->orWhere('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        $students = $query->get();

        // 2. Appointments
        $appointments = Appointment::with('user.studentProfile')
                        ->whereIn('status', ['Pending', 'Approved'])
                        ->orderBy('appointment_date', 'asc')
                        ->get();

        // 3. Analytics Data
        $totalApps = Appointment::count();
        $freshmenApps = Appointment::where('appointment_type', 'LIKE', '%Freshmen%')->count();
        $ojtApps = Appointment::where('appointment_type', 'LIKE', '%COE%')->orWhere('appointment_type', 'LIKE', '%OJT%')->count();

        // --- UNIVERSAL CHART DATA (Works on MySQL & Postgres) ---
        $months = range(1, 12);
        $completedData = array_fill(0, 12, 0);
        $pendingData = array_fill(0, 12, 0);

        // Detect Database Driver to choose correct SQL syntax
        $dbDriver = DB::connection()->getDriverName();
        $monthFunc = $dbDriver === 'pgsql' ? 'EXTRACT(MONTH FROM appointment_date)' : 'MONTH(appointment_date)';

        $completedCounts = Appointment::select(DB::raw("$monthFunc as month"), DB::raw('count(*) as count'))
                            ->where('status', 'Completed')
                            ->whereYear('appointment_date', Carbon::now()->year)
                            ->groupBy('month')
                            ->pluck('count', 'month');

        $pendingCounts = Appointment::select(DB::raw("$monthFunc as month"), DB::raw('count(*) as count'))
                            ->whereIn('status', ['Pending', 'Approved'])
                            ->whereYear('appointment_date', Carbon::now()->year)
                            ->groupBy('month')
                            ->pluck('count', 'month');

        foreach ($months as $month) {
            if (isset($completedCounts[$month])) $completedData[$month - 1] = $completedCounts[$month];
            if (isset($pendingCounts[$month])) $pendingData[$month - 1] = $pendingCounts[$month];
        }

        // Comparison Data
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        $currentMonthCount = Appointment::whereYear('appointment_date', $currentMonth->year)->whereMonth('appointment_date', $currentMonth->month)->count();
        $lastMonthCount = Appointment::whereYear('appointment_date', $lastMonth->year)->whereMonth('appointment_date', $lastMonth->month)->count();

        $percentChange = 0;
        if ($lastMonthCount > 0) {
            $percentChange = (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100;
        } elseif ($currentMonthCount > 0) {
            $percentChange = 100;
        }

        $comparisonData = [
            'labels' => [$lastMonth->format('F Y'), $currentMonth->format('F Y')],
            'data' => [$lastMonthCount, $currentMonthCount],
            'percent' => round($percentChange, 1),
            'status' => $percentChange >= 0 ? 'Increase' : 'Decrease'
        ];

        // 4. Fetch Announcements
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        return view('staff_dashboard', compact(
            'students', 'appointments',
            'totalApps', 'freshmenApps', 'ojtApps',
            'completedData', 'pendingData', 'comparisonData',
            'announcements'
        ));
    }

    // ... (Keep the rest of your functions: storeAnnouncement, deleteAnnouncement, getCalendarData, setDailyLimit, updateStatus) ...
    // Note: Make sure you copy/paste your existing functions below this line!
    // Since I can't see your full file, just paste the functions we wrote earlier here.

    public function storeAnnouncement(Request $request) { Announcement::create($request->all()); return back()->with('success', 'Posted!'); }
    public function deleteAnnouncement($id) { Announcement::destroy($id); return back()->with('success', 'Deleted.'); }

    public function getCalendarData() {
        $bookings = Appointment::selectRaw('appointment_date, count(*) as total')->whereIn('status', ['Pending', 'Approved'])->groupBy('appointment_date')->pluck('total', 'appointment_date');
        $limits = DailyLimit::pluck('limit', 'date');
        return response()->json(['bookings' => $bookings, 'limits' => $limits]);
    }
    public function setDailyLimit(Request $request) {
        DailyLimit::updateOrCreate(['date' => $request->date], ['limit' => $request->limit]);
        return response()->json(['success' => true]);
    }
    public function updateStatus(Request $request) {
        $profile = StudentProfile::find($request->profile_id);
        if ($profile) {
            $profile->medical_status = $request->medical_status;
            $profile->date_checked = $request->date_checked;
            $profile->save();
            $active = Appointment::where('user_id', $profile->user_id)->whereIn('status', ['Pending', 'Approved'])->first();
            if($active) { $active->status = 'Completed'; $active->save(); }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
