@extends('layouts.app')
@section('title', 'Add Medical Record')

@section('content')
<div class="page-header">
    <div>
        <h4>Add Medical Record</h4>
        <p>Record patient diagnosis and prescription</p>
    </div>
    <a href="{{ route('medical-records.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card">
    <form action="{{ route('medical-records.store') }}" method="POST">
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
                <label class="form-label">Record Date</label>
                <input type="date" name="record_date"
                       class="form-control @error('record_date') is-invalid @enderror"
                       value="{{ old('record_date') }}">
                @error('record_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Diagnosis <span class="text-muted">(optional)</span></label>
                <input type="text" name="diagnosis"
                       class="form-control @error('diagnosis') is-invalid @enderror"
                       value="{{ old('diagnosis') }}" placeholder="e.g. Hypertension">
                @error('diagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Prescription <span class="text-muted">(optional)</span></label>
                <textarea name="prescription" rows="3" class="form-control"
                          placeholder="Medications and dosage...">{{ old('prescription') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
                <textarea name="notes" rows="3" class="form-control"
                          placeholder="Additional notes...">{{ old('notes') }}</textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow">
                    <i class="bi bi-save me-1"></i> Save Record
                </button>
                <a href="{{ route('medical-records.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
