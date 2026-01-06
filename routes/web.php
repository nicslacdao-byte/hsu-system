<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| GUEST ROUTES (Accessible when NOT logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth'); })->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // REMOVED THE ADMIN ROUTE FROM HERE
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Accessible only when LOGGED IN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- SMART DASHBOARD ROUTE ---
    Route::get('/', function (Request $request) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return app(AdminController::class)->index();
        }
        elseif ($user->role === 'staff') {
            return app(StaffController::class)->index($request);
        }
        else {
            return app(AppointmentController::class)->index();
        }
    })->name('dashboard');


    // --- ADMIN ROUTES ---
    Route::post('/admin/create-staff', [AdminController::class, 'createStaff']);
    Route::get('/admin/delete-user/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/update-schedule', [AdminController::class, 'updateSchedule']);

    // *** MOVED TO HERE (CORRECT LOCATION) ***
    Route::post('/admin/update-staff-name', [AdminController::class, 'updateStaffName']);


    // --- STAFF ROUTES ---
    Route::post('/staff/update-status', [StaffController::class, 'updateStatus']);
    Route::get('/staff/calendar-data', [StaffController::class, 'getCalendarData']);
    Route::post('/staff/set-limit', [StaffController::class, 'setDailyLimit']);
    Route::post('/staff/post-announcement', [StaffController::class, 'storeAnnouncement']);
    Route::get('/staff/delete-announcement/{id}', [StaffController::class, 'deleteAnnouncement']);


    // --- STUDENT ROUTES ---
    Route::post('/save-student-info', [StudentController::class, 'store']);
    Route::post('/book-appointment-save', [AppointmentController::class, 'store']);
    Route::post('/cancel-appointment/{id}', [AppointmentController::class, 'cancel']);


    // --- SHARED ROUTES ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/book-appointment', function () { return redirect('/'); });
    Route::get('/my-appointments', function () { return redirect('/'); });
    Route::get('/medical-records', function () { return redirect('/'); });

});

    Route::get('/run-seeder', function () {
    try {
        Artisan::call('db:seed');
        return 'SUCCESS: Seeder ran! You can now login as Admin (0000).';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
