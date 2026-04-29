<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $doctorId = $user->isDoctor() ? ($user->doctor?->id ?? 0) : null;

        $patientScope = Patient::query()
            ->when($doctorId, function ($query) use ($doctorId) {
                $query->where(function ($q) use ($doctorId) {
                    $q->whereHas('appointments', fn($appt) => $appt->where('doctor_id', $doctorId))
                      ->orWhereHas('medicalRecords', fn($record) => $record->where('doctor_id', $doctorId));
                });
            });

        $appointmentScope = Appointment::query()
            ->when($doctorId, fn($query) => $query->where('doctor_id', $doctorId));

        $billingScope = Billing::query()
            ->when($doctorId, fn($query) => $query->where('doctor_id', $doctorId));

        $totalPatients     = (clone $patientScope)->count();
        $totalDoctors      = Doctor::count();
        $todayAppointments = (clone $appointmentScope)->whereDate('appointment_date', today())->count();
        $totalRevenue      = (clone $billingScope)->where('status', 'Paid')->sum('amount');
        $malePatients      = (clone $patientScope)->where('gender', 'Male')->count();
        $femalePatients    = (clone $patientScope)->where('gender', 'Female')->count();
        $recentPatients    = (clone $patientScope)->latest()->take(5)->get();
        $todayAppts        = (clone $appointmentScope)->with(['patient', 'doctor'])
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
