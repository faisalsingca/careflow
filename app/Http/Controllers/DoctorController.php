<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $doctors = Doctor::when($search, function ($query) use ($search) {
            $query->where('first_name',      'like', "%{$search}%")
                  ->orWhere('last_name',      'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('email',          'like', "%{$search}%");
        })->latest()->paginate(10)->withQueryString();

        return view('doctors.index', compact('doctors', 'search'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'contact_number' => 'required|string|max:20',
            'email'          => 'required|email|unique:doctors,email',
            'status'         => 'required|in:Active,Inactive',
        ]);

        Doctor::create($request->all());

        return redirect()->route('doctors.index')
                         ->with('success', 'Doctor added successfully.');
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'contact_number' => 'required|string|max:20',
            'email'          => 'required|email|unique:doctors,email,' . $doctor->id,
            'status'         => 'required|in:Active,Inactive',
        ]);

        $doctor->update($request->all());

        return redirect()->route('doctors.index')
                         ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')
                         ->with('success', 'Doctor deleted successfully.');
    }
}