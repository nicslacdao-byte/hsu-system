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
    Route::get('/', function () { return view('welcome'); }); // Main Landing Page
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'registerPost']); // Make sure this matches AuthController
    Route::post('/login', [AuthController::class, 'loginPost']);       // Make sure this matches AuthController
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Accessible only when LOGGED IN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- SMART DASHBOARD ROUTE ---
    // We let the AuthController handle the "Admin vs Student" check
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');


    // --- ADMIN ROUTES ---
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/create-staff', [AdminController::class, 'createStaff']);
    Route::get('/admin/delete-user/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/update-schedule', [AdminController::class, 'updateSchedule']);
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

    // Appointment Pages
    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('book.appointment');
    Route::get('/my-appointments', [AppointmentController::class, 'userAppointments'])->name('my.appointments');
    Route::get('/medical-records', [AppointmentController::class, 'medicalRecords'])->name('medical.records');


    // --- SHARED ROUTES ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// --- SEEDER ROUTE (Run once then remove) ---
Route::get('/run-seeder', function () {
    try {
        Artisan::call('db:seed');
        return 'SUCCESS: Seeder ran! You can now login as Admin (0000).';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
