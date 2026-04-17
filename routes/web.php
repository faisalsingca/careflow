<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── PATIENTS ──────────────────────────────────────────
    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');
    Route::middleware(['staff'])->group(function () {
        Route::get('patients/create',         [PatientController::class, 'create'])->name('patients.create');
        Route::post('patients',               [PatientController::class, 'store'])->name('patients.store');
        Route::get('patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('patients/{patient}',      [PatientController::class, 'update'])->name('patients.update');
        Route::delete('patients/{patient}',   [PatientController::class, 'destroy'])->name('patients.destroy');
    });

    // ── APPOINTMENTS ──────────────────────────────────────
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::middleware(['staff'])->group(function () {
        Route::get('appointments/create',             [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments',                   [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('appointments/{appointment}',      [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('appointments/{appointment}',   [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    // ── DOCTORS ───────────────────────────────────────────
    Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::middleware(['admin'])->group(function () {
        Route::get('doctors/create',        [DoctorController::class, 'create'])->name('doctors.create');
        Route::post('doctors',              [DoctorController::class, 'store'])->name('doctors.store');
        Route::get('doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('doctors/{doctor}',      [DoctorController::class, 'update'])->name('doctors.update');
        Route::delete('doctors/{doctor}',   [DoctorController::class, 'destroy'])->name('doctors.destroy');
    });

    // ── MEDICAL RECORDS ───────────────────────────────────
    // Use only 'doctor' middleware (which already allows admin + doctor)
    Route::middleware(['doctor'])->group(function () {
        Route::get('medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
        Route::get('medical-records/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
        Route::post('medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::get('medical-records/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
        Route::put('medical-records/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
        Route::delete('medical-records/{medicalRecord}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
    });

    // ── BILLING ───────────────────────────────────────────
    // Use only 'doctor' middleware (same logic)
    Route::get('billings', [BillingController::class, 'index'])->name('billings.index');
    Route::middleware(['doctor'])->group(function () {
        Route::get('billings/create',         [BillingController::class, 'create'])->name('billings.create');
        Route::post('billings',               [BillingController::class, 'store'])->name('billings.store');
        Route::get('billings/{billing}/edit', [BillingController::class, 'edit'])->name('billings.edit');
        Route::put('billings/{billing}',      [BillingController::class, 'update'])->name('billings.update');
        Route::delete('billings/{billing}',   [BillingController::class, 'destroy'])->name('billings.destroy');
    });

    // ── PROFILE ───────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';