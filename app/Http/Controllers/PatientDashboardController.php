<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return view('patient.no-profile');
        }

        $appointments   = $patient->appointments()->with('doctor')->latest('appointment_date')->get();
        $medicalRecords = $patient->medicalRecords()->with('doctor')->latest('record_date')->take(5)->get();
        $billings       = $patient->billings()->with('doctor')->latest('billing_date')->take(5)->get();

        return view('patient.dashboard', compact('patient', 'appointments', 'medicalRecords', 'billings'));
    }
}