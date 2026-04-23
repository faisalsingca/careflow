<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    private function canModify(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'doctor']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $billings = Billing::with(['patient', 'doctor'])
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('patient', fn($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"))
                      ->orWhereHas('doctor',  fn($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
            })->latest()->paginate(10)->withQueryString();

        return view('billings.index', compact('billings', 'search'));
    }

    public function create()
    {
        if (!$this->canModify()) abort(403);
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('billings.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        if (!$this->canModify()) abort(403);

        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'billing_date'   => 'required|date',
            'description'    => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'doctor_percent' => 'required|numeric|min:0|max:100',
            'status'         => 'required|in:Unpaid,Paid,Cancelled',
            'notes'          => 'nullable|string',
        ]);

        $amount         = $request->amount;
        $doctorPercent  = $request->doctor_percent;
        $doctorShare    = round($amount * $doctorPercent / 100, 2);
        $clinicShare    = round($amount - $doctorShare, 2);

        Billing::create([
            'patient_id'     => $request->patient_id,
            'doctor_id'      => $request->doctor_id,
            'billing_date'   => $request->billing_date,
            'description'    => $request->description,
            'amount'         => $amount,
            'doctor_percent' => $doctorPercent,
            'doctor_share'   => $doctorShare,
            'clinic_share'   => $clinicShare,
            'status'         => $request->status,
            'notes'          => $request->notes,
        ]);

        return redirect()->route('billings.index')->with('success', 'Billing record created.');
    }

    public function show(Billing $billing)
    {
        return redirect()->route('billings.edit', $billing);
    }

    public function edit(Billing $billing)
    {
        if (!$this->canModify()) abort(403);
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('status', 'Active')->orderBy('first_name')->get();
        return view('billings.edit', compact('billing', 'patients', 'doctors'));
    }

    public function update(Request $request, Billing $billing)
    {
        if (!$this->canModify()) abort(403);

        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'billing_date'   => 'required|date',
            'description'    => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'doctor_percent' => 'required|numeric|min:0|max:100',
            'status'         => 'required|in:Unpaid,Paid,Cancelled',
            'notes'          => 'nullable|string',
        ]);

        $amount        = $request->amount;
        $doctorPercent = $request->doctor_percent;
        $doctorShare   = round($amount * $doctorPercent / 100, 2);
        $clinicShare   = round($amount - $doctorShare, 2);

        $billing->update([
            'patient_id'     => $request->patient_id,
            'doctor_id'      => $request->doctor_id,
            'billing_date'   => $request->billing_date,
            'description'    => $request->description,
            'amount'         => $amount,
            'doctor_percent' => $doctorPercent,
            'doctor_share'   => $doctorShare,
            'clinic_share'   => $clinicShare,
            'status'         => $request->status,
            'notes'          => $request->notes,
        ]);

        return redirect()->route('billings.index')->with('success', 'Billing record updated.');
    }

    public function destroy(Billing $billing)
    {
        if (!$this->canModify()) abort(403);
        $billing->delete();
        return redirect()->route('billings.index')->with('success', 'Billing record deleted.');
    }
}