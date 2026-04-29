@extends('layouts.app')
@section('title', 'Edit Medical Record')

@section('content')
<div class="page-header">
    <div>
        <h4>Edit Medical Record</h4>
        <p>Update the medical record below</p>
    </div>
    <a href="{{ route('medical-records.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card">
    <form action="{{ route('medical-records.update', $medicalRecord) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient</label>
                <select name="patient_id" class="form-select" @if(Auth::user()->role !== 'admin') disabled @endif>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}"
                            {{ old('patient_id', $medicalRecord->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Doctor</label>
                <select name="doctor_id" class="form-select" @if(Auth::user()->role !== 'admin') disabled @endif>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}"
                            {{ old('doctor_id', $medicalRecord->doctor_id) == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Record Date</label>
                <input type="date" name="record_date" class="form-control" @if(Auth::user()->role !== 'admin') disabled @endif
                       value="{{ old('record_date', $medicalRecord->record_date) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Diagnosis <span class="text-muted">(optional)</span></label>
                <input type="text" name="diagnosis" class="form-control"
                       value="{{ old('diagnosis', $medicalRecord->diagnosis) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Prescription <span class="text-muted">(optional)</span></label>
                <textarea name="prescription" rows="3" class="form-control">{{ old('prescription', $medicalRecord->prescription) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
                <textarea name="notes" rows="3" class="form-control">{{ old('notes', $medicalRecord->notes) }}</textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow">
                    <i class="bi bi-save me-1"></i> Update Record
                </button>
                <a href="{{ route('medical-records.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
