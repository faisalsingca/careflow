<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort   = $request->input('sort', 'date_desc');

        $appointments = Appointment::with(['patient', 'doctor'])
            ->when($search, function ($query) use ($search) {
                $query->where('reason', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('patient', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('doctor', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      });
            })
            ->when($sort === 'date_asc',  fn($q) => $q->orderBy('appointment_date', 'asc')->orderBy('appointment_time', 'asc'))
            ->when($sort === 'date_desc', fn($q) => $q->orderBy('appointment_date', 'desc')->orderBy('appointment_time', 'asc'))
            ->when($sort === 'time_asc',  fn($q) => $q->orderBy('appointment_time', 'asc'))
            ->when($sort === 'time_desc', fn($q) => $q->orderBy('appointment_time', 'desc'))
            ->paginate(10)->withQueryString();

        return view('appointments.index', compact('appointments', 'search', 'sort'));
    }

    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:255',
            'status'           => 'required|in:Pending,Confirmed,Completed,Cancelled',
            'notes'            => 'nullable|string',
        ]);

        Appointment::create($request->all());

        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment booked successfully.');
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:255',
            'status'           => 'required|in:Pending,Confirmed,Completed,Cancelled',
            'notes'            => 'nullable|string',
        ]);

        $appointment->update($request->all());

        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment cancelled successfully.');
    }
}