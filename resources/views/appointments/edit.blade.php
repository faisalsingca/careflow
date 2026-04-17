@extends('layouts.app')
@section('title', 'Edit Appointment')

@section('content')
<div class="page-header">
    <div>
        <h4>Edit Appointment</h4>
        <p>Update the appointment details below</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card">
    <form action="{{ route('appointments.update', $appointment) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient</label>
                <select name="patient_id" class="form-select">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}"
                            {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
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
                            {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->full_name }} — {{ $doctor->specialization }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control"
                       value="{{ old('appointment_date', $appointment->appointment_date) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Appointment Time</label>
                <input type="time" name="appointment_time" class="form-control"
                       value="{{ old('appointment_time', $appointment->appointment_time) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" class="form-control"
                       value="{{ old('reason', $appointment->reason) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['Pending','Confirmed','Completed','Cancelled'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $appointment->status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
                <textarea name="notes" rows="3" class="form-control">{{ old('notes', $appointment->notes) }}</textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow">
                    <i class="bi bi-save me-1"></i> Update Appointment
                </button>
                <a href="{{ route('appointments.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection