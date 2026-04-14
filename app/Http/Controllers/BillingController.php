<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $billings = Billing::with(['patient', 'doctor'])
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                      ->orWhere('status',    'like', "%{$search}%")
                      ->orWhereHas('patient', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('doctor', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      });
            })->latest()->paginate(10)->withQueryString();

        return view('billings.index', compact('billings', 'search'));
    }

    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('billings.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'billing_date' => 'required|date',
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'status'       => 'required|in:Unpaid,Paid,Cancelled',
            'notes'        => 'nullable|string',
        ]);

        Billing::create($request->all());

        return redirect()->route('billings.index')
                         ->with('success', 'Billing record created successfully.');
    }

    public function edit(Billing $billing)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('billings.edit', compact('billing', 'patients', 'doctors'));
    }

    public function update(Request $request, Billing $billing)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'billing_date' => 'required|date',
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'status'       => 'required|in:Unpaid,Paid,Cancelled',
            'notes'        => 'nullable|string',
        ]);

        $billing->update($request->all());

        return redirect()->route('billings.index')
                         ->with('success', 'Billing record updated successfully.');
    }

    public function destroy(Billing $billing)
    {
        $billing->delete();
        return redirect()->route('billings.index')
                         ->with('success', 'Billing record deleted successfully.');
    }
}