<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    private function currentDoctorId(): ?int
    {
        return Auth::user()->doctor?->id;
    }

    private function canViewRecords(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'staff', 'doctor']);
    }

    private function canManageAllRecords(): bool
    {
        return Auth::user()->role === 'admin';
    }

    private function currentDoctorOrFail(): int
    {
        return $this->currentDoctorId() ?? abort(403, 'No doctor profile linked to this account.');
    }

    private function authorizeRecordAccess(MedicalRecord $medicalRecord): void
    {
        if ($this->canManageAllRecords() || Auth::user()->role === 'staff') {
            return;
        }

        if (Auth::user()->role === 'doctor' && $medicalRecord->doctor_id === $this->currentDoctorId()) {
            return;
        }

        abort(403);
    }

    private function authorizeRecordEdit(MedicalRecord $medicalRecord): void
    {
        if ($this->canManageAllRecords()) {
            return;
        }

        if (Auth::user()->role === 'doctor' && $medicalRecord->doctor_id === $this->currentDoctorId()) {
            return;
        }

        abort(403);
    }

    public function index(Request $request)
    {
        if (!$this->canViewRecords()) abort(403);

        $search = $request->input('search');
        $isDoctorView = Auth::user()->role === 'doctor';

        if ($isDoctorView) {
            $doctorId = $this->currentDoctorOrFail();

            $patients = Patient::query()
                ->whereHas('medicalRecords', function ($query) use ($doctorId) {
                    $query->where('doctor_id', $doctorId);
                })
                ->when($search, function ($query) use ($search, $doctorId) {
                    $query->where(function ($patientQuery) use ($search, $doctorId) {
                        $patientQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereHas('medicalRecords', function ($recordQuery) use ($search, $doctorId) {
                                $recordQuery->where('doctor_id', $doctorId)
                                    ->where(function ($historyQuery) use ($search) {
                                        $historyQuery->where('diagnosis', 'like', "%{$search}%")
                                            ->orWhere('prescription', 'like', "%{$search}%")
                                            ->orWhere('notes', 'like', "%{$search}%");
                                    });
                            });
                    });
                })
                ->withCount([
                    'medicalRecords as doctor_visit_count' => function ($query) use ($doctorId) {
                        $query->where('doctor_id', $doctorId);
                    },
                ])
                ->with([
                    'medicalRecords' => function ($query) use ($doctorId) {
                        $query->where('doctor_id', $doctorId)
                            ->with('doctor')
                            ->orderByDesc('record_date')
                            ->orderByDesc('id');
                    },
                ])
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->paginate(10)
                ->withQueryString();

            return view('medical-records.index', compact('patients', 'search', 'isDoctorView'));
        }

        $records = MedicalRecord::with(['patient', 'doctor'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('diagnosis', 'like', "%{$search}%")
                      ->orWhere('prescription', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%")
                      ->orWhereHas('patient', function ($q2) use ($search) {
                          $q2->where('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('doctor', function ($q2) use ($search) {
                          $q2->where('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%");
                      });
                });
            })->latest()->paginate(10)->withQueryString();

        return view('medical-records.index', compact('records', 'search', 'isDoctorView'));
    }

    public function patientHistory(Patient $patient)
    {
        if (!$this->canViewRecords()) abort(403);

        $user = Auth::user();
        $query = $patient->medicalRecords()->with('doctor')->latest('record_date')->latest('id');

        if ($user->role === 'doctor') {
            $doctorId = $this->currentDoctorOrFail();

            $query->where('doctor_id', $doctorId);
        }

        $records = $query->get();

        if ($user->role === 'doctor' && $records->isEmpty()) {
            abort(403);
        }

        return view('medical-records.history', compact('patient', 'records'));
    }

    public function create()
    {
        if (!$this->canManageAllRecords()) abort(403);

        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('medical-records.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        if (!$this->canManageAllRecords()) abort(403);

        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'record_date'  => 'required|date',
            'diagnosis'    => 'nullable|string|max:255',
            'prescription' => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('medical-records.index')
                         ->with('success', 'Medical record added successfully.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $this->authorizeRecordAccess($medicalRecord);

        if (Auth::user()->role === 'staff') {
            return redirect()->route('medical-records.index');
        }

        return redirect()->route('medical-records.edit', $medicalRecord);
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $this->authorizeRecordEdit($medicalRecord);

        $patients = $this->canManageAllRecords()
            ? Patient::orderBy('first_name')->get()
            : Patient::whereKey($medicalRecord->patient_id)->get();

        $doctors = $this->canManageAllRecords()
            ? Doctor::where('status', 'Active')->orderBy('first_name')->get()
            : Doctor::whereKey($medicalRecord->doctor_id)->get();

        return view('medical-records.edit', compact('medicalRecord', 'patients', 'doctors'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $this->authorizeRecordEdit($medicalRecord);

        $request->validate([
            'patient_id'   => $this->canManageAllRecords() ? 'required|exists:patients,id' : 'sometimes',
            'doctor_id'    => $this->canManageAllRecords() ? 'required|exists:doctors,id' : 'sometimes',
            'record_date'  => $this->canManageAllRecords() ? 'required|date' : 'sometimes|date',
            'diagnosis'    => 'nullable|string|max:255',
            'prescription' => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);

        $data = $request->only(['diagnosis', 'prescription', 'notes']);

        if ($this->canManageAllRecords()) {
            $data = array_merge($data, $request->only(['patient_id', 'doctor_id', 'record_date']));
        }

        $medicalRecord->update($data);

        return redirect()->route('medical-records.index')
                         ->with('success', 'Medical record updated successfully.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        if (!$this->canManageAllRecords()) abort(403);

        $medicalRecord->delete();
        return redirect()->route('medical-records.index')
                         ->with('success', 'Medical record deleted successfully.');
    }
}
