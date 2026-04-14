<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Billing;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatients     = Patient::count();
        $totalDoctors      = Doctor::count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $totalRevenue      = Billing::where('status', 'Paid')->sum('amount');
        $malePatients      = Patient::where('gender', 'Male')->count();
        $femalePatients    = Patient::where('gender', 'Female')->count();
        $recentPatients    = Patient::latest()->take(5)->get();
        $todayAppts        = Appointment::with(['patient', 'doctor'])
                                        ->whereDate('appointment_date', today())
                                        ->orderBy('appointment_time')
                                        ->take(4)
                                        ->get();

        return view('dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'todayAppointments',
            'totalRevenue',
            'malePatients',
            'femalePatients',
            'recentPatients',
            'todayAppts'
        ));
    }
}