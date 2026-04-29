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

        $records = MedicalRecord::with(['patient', 'doctor'])
            ->when(Auth::user()->role === 'doctor', function ($query) {
                $query->where('doctor_id', $this->currentDoctorId() ?? 0);
            })
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

        return view('medical-records.index', compact('records', 'search'));
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
