@extends('layouts.app')
@section('title', 'Edit Billing')

@section('content')
<div class="page-header">
    <div>
        <h4>Edit Billing</h4>
        <p>Update the billing record below</p>
    </div>
    <a href="{{ route('billings.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card">
    <form action="{{ route('billings.update', $billing) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient</label>
                <select name="patient_id" class="form-select">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}"
                            {{ old('patient_id', $billing->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Doctor</label>
                <select name="doctor_id" class="form-select">
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}"
                            {{ old('doctor_id', $billing->doctor_id) == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Billing Date</label>
                <input type="date" name="billing_date" class="form-control"
                       value="{{ old('billing_date', $billing->billing_date) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Amount (₱)</label>
                <input type="number" step="0.01" name="amount" class="form-control"
                       value="{{ old('amount', $billing->amount) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control"
                       value="{{ old('description', $billing->description) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['Unpaid','Paid','Cancelled'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $billing->status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
                <textarea name="notes" rows="3" class="form-control">{{ old('notes', $billing->notes) }}</textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow">
                    <i class="bi bi-save me-1"></i> Update Billing
                </button>
                <a href="{{ route('billings.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection