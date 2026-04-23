<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// ==================== LANDING PAGE ====================
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return $role === 'patient'
            ? redirect()->route('patient.dashboard')
            : redirect()->route('dashboard');
    }
    return view('welcome');
})->name('landing');

// ==================== STAFF / ADMIN / DOCTOR ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // Main dashboard (not for patients)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patient Management
    Route::resource('patients', PatientController::class);

    // Doctor Management
    Route::resource('doctors', DoctorController::class);
    Route::patch('doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])->name('doctors.toggle-status');
    Route::post('patients/{patient}/link-user', [PatientController::class, 'linkUser'])->name('patients.link-user');

    // Appointments — staff/admin full CRUD + patient booking
    Route::get('appointments/book',  [AppointmentController::class, 'bookCreate'])->name('appointments.book');
    Route::post('appointments/book', [AppointmentController::class, 'bookStore'])->name('appointments.book.store');
    Route::resource('appointments', AppointmentController::class);
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');

    // Medical Records
    Route::resource('medical-records', MedicalRecordController::class);

    // Billing
    Route::resource('billings', BillingController::class);

    // Room Management
    Route::resource('rooms', RoomController::class);

    // Inventory Management
    Route::resource('inventory', InventoryController::class);

    // Transaction Management
    Route::resource('transactions', TransactionController::class);

    // Admin User Management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'create', 'store']);
    });
});

// ==================== PATIENT PORTAL ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/my-dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
});

// Auth routes
require __DIR__.'/auth.php';