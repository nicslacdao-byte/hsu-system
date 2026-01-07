<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
// --- CRITICAL IMPORTS: These lines prevent "Class not found" errors ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Public Routes (No Login Needed) ---
Route::get('/', function () {
    return view('welcome'); // ERROR NOTE: If your homepage is named 'index', change 'welcome' to 'index'
});

// Login & Register Pages
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 2. Protected Routes (Must be Logged In) ---
Route::middleware(['auth'])->group(function () {

    // Main Dashboard (The AuthController decides if you go to Admin or Student page)
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Admin Dashboard (Specific Route)
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin Actions
    Route::post('/admin/create-staff', [AdminController::class, 'createStaff']);
    Route::get('/admin/delete-user/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/update-schedule', [AdminController::class, 'updateSchedule']);
    Route::post('/admin/update-staff-name', [AdminController::class, 'updateStaffName']);

    // Staff Actions
    Route::post('/staff/update-status', [StaffController::class, 'updateStatus']);
    Route::get('/staff/calendar-data', [StaffController::class, 'getCalendarData']);
    Route::post('/staff/set-limit', [StaffController::class, 'setDailyLimit']);
    Route::post('/staff/post-announcement', [StaffController::class, 'storeAnnouncement']);
    Route::get('/staff/delete-announcement/{id}', [StaffController::class, 'deleteAnnouncement']);

    // Student/Appointment Actions
    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('book.appointment');
    Route::post('/book-appointment-save', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/my-appointments', [AppointmentController::class, 'userAppointments'])->name('my.appointments');
    Route::get('/medical-records', [AppointmentController::class, 'medicalRecords'])->name('medical.records');
    Route::post('/cancel-appointment/{id}', [AppointmentController::class, 'cancel']);
    Route::post('/save-student-info', [StudentController::class, 'store']);
});

// --- 3. Seeder Route (Run once then delete) ---
Route::get('/run-seeder', function () {
    try {
        Artisan::call('db:seed');
        return 'SUCCESS: Admin (0000) created.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
