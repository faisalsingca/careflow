<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $records = MedicalRecord::with(['patient', 'doctor'])
            ->when($search, function ($query) use ($search) {
                $query->where('diagnosis',    'like', "%{$search}%")
                      ->orWhere('prescription', 'like', "%{$search}%")
                      ->orWhereHas('patient', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('doctor', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      });
            })->latest()->paginate(10)->withQueryString();

        return view('medical-records.index', compact('records', 'search'));
    }

    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('medical-records.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'record_date'  => 'required|date',
            'diagnosis'    => 'required|string|max:255',
            'prescription' => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('medical-records.index')
                         ->with('success', 'Medical record added successfully.');
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('medical-records.edit', compact('medicalRecord', 'patients', 'doctors'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'record_date'  => 'required|date',
            'diagnosis'    => 'required|string|max:255',
            'prescription' => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);

        $medicalRecord->update($request->all());

        return redirect()->route('medical-records.index')
                         ->with('success', 'Medical record updated successfully.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return redirect()->route('medical-records.index')
                         ->with('success', 'Medical record deleted successfully.');
    }
}