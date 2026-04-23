<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    // Only admin and staff can create/edit/delete
    private function canModify(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'staff']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::when($search, function ($query) use ($search) {
            $query->where('first_name',    'like', "%{$search}%")
                  ->orWhere('last_name',      'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('address',        'like', "%{$search}%");
        })->latest()->paginate(10)->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        if (!$this->canModify()) abort(403);
        return view('patients.create');
    }

    public function store(Request $request)
    {
        if (!$this->canModify()) abort(403);

        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        Patient::create($request->only([
            'first_name','last_name','date_of_birth','gender',
            'contact_number','address','notes'
        ]));

        return redirect()->route('patients.index')
                         ->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient)
    {
        return redirect()->route('patients.edit', $patient);
    }

    public function edit(Patient $patient)
    {
        if (!$this->canModify()) abort(403);
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        if (!$this->canModify()) abort(403);

        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        $patient->update($request->only([
            'first_name','last_name','date_of_birth','gender',
            'contact_number','address','notes'
        ]));

        return redirect()->route('patients.index')
                         ->with('success', 'Patient updated successfully.');
    }
    public function linkUser(Request $request, Patient $patient)
{
    if (Auth::user()->role !== 'admin') abort(403);

    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $patient->update(['user_id' => $request->user_id]);

    return back()->with('success', 'User linked to patient successfully.');
}
    public function destroy(Patient $patient)
    {
        if (!$this->canModify()) abort(403);
        $patient->delete();
        return redirect()->route('patients.index')
                         ->with('success', 'Patient deleted successfully.');
    }
}