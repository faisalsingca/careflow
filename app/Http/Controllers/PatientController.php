<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::when($search, function ($query) use ($search) {
            $query->where('first_name',     'like', "%{$search}%")
                  ->orWhere('last_name',       'like', "%{$search}%")
                  ->orWhere('contact_number',  'like', "%{$search}%")
                  ->orWhere('address',         'like', "%{$search}%");
        })->latest()->paginate(10)->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        Patient::create($request->all());

        return redirect()->route('patients.index')
                         ->with('success', 'Patient added successfully.');
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')
                         ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')
                         ->with('success', 'Patient deleted successfully.');
    }
}