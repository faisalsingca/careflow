@extends('layouts.app')
@section('page-title', 'Add Billing')

@section('content')
<div class="page-header">
    <div>
        <h4>Add Billing</h4>
        <p>Create a new billing record with revenue split</p>
    </div>
    <a href="{{ route('billings.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card" x-data="{ amount: 0, percent: 0, doctorShare: 0, clinicShare: 0,
    calc() {
        let a = parseFloat(this.amount) || 0;
        let p = parseFloat(this.percent) || 0;
        this.doctorShare = (a * p / 100).toFixed(2);
        this.clinicShare = (a - this.doctorShare).toFixed(2);
    }
}">
    <form action="{{ route('billings.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient</label>
                <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror">
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Doctor</label>
                <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Billing Date</label>
                <input type="date" name="billing_date" class="form-control @error('billing_date') is-invalid @enderror" value="{{ old('billing_date') }}">
                @error('billing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" placeholder="e.g. Consultation Fee">
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Amount + Split --}}
            <div class="col-md-4">
                <label class="form-label">Total Amount (₱)</label>
                <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror"
                       value="{{ old('amount') }}" placeholder="0.00"
                       x-model="amount" @input="calc()">
                @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Doctor's Cut (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="doctor_percent"
                       class="form-control @error('doctor_percent') is-invalid @enderror"
                       value="{{ old('doctor_percent', 0) }}" placeholder="e.g. 30"
                       x-model="percent" @input="calc()">
                @error('doctor_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Unpaid"    {{ old('status') == 'Unpaid'    ? 'selected' : '' }}>Unpaid</option>
                    <option value="Paid"      {{ old('status') == 'Paid'      ? 'selected' : '' }}>Paid</option>
                    <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Live Split Preview --}}
            <div class="col-12">
                <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:4px;">Doctor Gets</div>
                        <div style="font-size:22px;font-weight:700;color:var(--emerald);">₱<span x-text="doctorShare">0.00</span></div>
                        <div style="font-size:11px;color:var(--text-muted);" x-text="percent + '% of total'"></div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:4px;">Clinic Gets</div>
                        <div style="font-size:22px;font-weight:700;color:var(--brand);">₱<span x-text="clinicShare">0.00</span></div>
                        <div style="font-size:11px;color:var(--text-muted);" x-text="(100 - percent) + '% of total'"></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
                <textarea name="notes" rows="3" class="form-control" placeholder="Additional notes...">{{ old('notes') }}</textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow"><i class="bi bi-save me-1"></i> Save Billing</button>
                <a href="{{ route('billings.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection