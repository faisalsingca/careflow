<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    private function canModify(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'staff']);
    }

    private function currentDoctorId(): ?int
    {
        return Auth::user()->doctor?->id;
    }

    private function createMedicalRecordForCompletedAppointment(Appointment $appointment): void
    {
        if ($appointment->status !== 'Completed' || !$appointment->doctor_id) {
            return;
        }

        MedicalRecord::firstOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'patient_id'  => $appointment->patient_id,
                'doctor_id'   => $appointment->doctor_id,
                'record_date' => now()->toDateString(),
                'diagnosis'   => null,
                'notes'       => $appointment->notes,
            ]
        );
    }

    public function index(Request $request)
    {
        $user   = Auth::user();
        $search = $request->input('search');
        $sort   = $request->input('sort', 'date_desc');

        $query = Appointment::with(['patient', 'doctor']);

        // Patients only see their own appointments
        if ($user->isPatient()) {
            $patient = $user->patient;
            if (!$patient) {
                return view('appointments.index', [
                    'appointments' => collect(),
                    'search'       => $search,
                    'sort'         => $sort,
                    'isPaginated'  => false,
                ]);
            }
            $query->where('patient_id', $patient->id);
        }

        if ($user->isDoctor()) {
            $query->where('doctor_id', $this->currentDoctorId() ?? 0);
        }

        $query->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('reason', 'like', "%{$search}%")
                   ->orWhere('status', 'like', "%{$search}%")
                   ->orWhereHas('patient', fn($q3) => $q3->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"))
                   ->orWhereHas('doctor', fn($q3) => $q3->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
            });
        });

        $query->when($sort === 'date_asc',  fn($q) => $q->orderBy('appointment_date', 'asc')->orderBy('appointment_time', 'asc'))
              ->when($sort === 'date_desc', fn($q) => $q->orderBy('appointment_date', 'desc')->orderBy('appointment_time', 'asc'))
              ->when($sort === 'time_asc',  fn($q) => $q->orderBy('appointment_time', 'asc'))
              ->when($sort === 'time_desc', fn($q) => $q->orderBy('appointment_time', 'desc'));

        $appointments = $query->paginate(10)->withQueryString();

        return view('appointments.index', compact('appointments', 'search', 'sort'));
    }

    // Patient books their own appointment (no doctor, no status choice)
    public function bookCreate()
    {
        if (!Auth::user()->isPatient()) abort(403);
        if (!Auth::user()->patient) abort(403, 'No patient profile linked to your account.');
        return view('appointments.book');
    }

    public function bookStore(Request $request)
    {
        if (!Auth::user()->isPatient()) abort(403);

        $patient = Auth::user()->patient;
        if (!$patient) abort(403);

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:255',
        ]);

        Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => null,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason'           => $request->reason,
            'status'           => 'Pending',
            'notes'            => null,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment request submitted. Please wait for staff approval.');
    }

    // Staff/Admin create form (full control)
    public function create()
    {
        if (!$this->canModify()) abort(403);
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        if (!$this->canModify()) abort(403);

        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'nullable|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:255',
            'status'           => 'required|in:Pending,Confirmed,Completed,Cancelled',
            'notes'            => 'nullable|string',
        ]);

        if ($request->status === 'Completed' && !$request->doctor_id) {
            return back()->withInput()->withErrors(['doctor_id' => 'A doctor is required before completing an appointment.']);
        }

        $appointment = Appointment::create($request->only([
            'patient_id','doctor_id','appointment_date',
            'appointment_time','reason','status','notes'
        ]));

        $this->createMedicalRecordForCompletedAppointment($appointment);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        return redirect()->route('appointments.edit', $appointment);
    }

    public function edit(Appointment $appointment)
    {
        if (!$this->canModify()) abort(403);
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (!$this->canModify()) abort(403);

        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'nullable|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:255',
            'status'           => 'required|in:Pending,Confirmed,Completed,Cancelled',
            'notes'            => 'nullable|string',
        ]);

        if ($request->status === 'Completed' && !$request->doctor_id) {
            return back()->withInput()->withErrors(['doctor_id' => 'A doctor is required before completing an appointment.']);
        }

        $appointment->update($request->only([
            'patient_id','doctor_id','appointment_date',
            'appointment_time','reason','status','notes'
        ]));

        $this->createMedicalRecordForCompletedAppointment($appointment->fresh());

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        if (!$this->canModify()) abort(403);
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        if (!$this->canModify()) abort(403);
        $request->validate(['status' => 'required|in:Pending,Confirmed,Completed,Cancelled']);
        if ($request->status === 'Completed' && !$appointment->doctor_id) {
            return back()->withErrors(['status' => 'Assign a doctor before marking this appointment completed.']);
        }
        $appointment->update(['status' => $request->status]);
        $this->createMedicalRecordForCompletedAppointment($appointment->fresh());
        return back()->with('success', 'Appointment status updated.');
    }
}
