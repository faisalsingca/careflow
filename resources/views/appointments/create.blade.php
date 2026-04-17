@extends('layouts.app')
@section('title', 'Book Appointment')

@section('content')
<div class="page-header">
    <div>
        <h4>Book Appointment</h4>
        <p>Schedule a new patient appointment</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card">
    <form action="{{ route('appointments.store') }}" method="POST">
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
                            Dr. {{ $doctor->full_name }} — {{ $doctor->specialization }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date"
                       class="form-control @error('appointment_date') is-invalid @enderror"
                       value="{{ old('appointment_date') }}">
                @error('appointment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Appointment Time</label>
                <input type="time" name="appointment_time"
                       class="form-control @error('appointment_time') is-invalid @enderror"
                       value="{{ old('appointment_time') }}">
                @error('appointment_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Reason</label>
                <input type="text" name="reason"
                       class="form-control @error('reason') is-invalid @enderror"
                       value="{{ old('reason') }}" placeholder="Reason for visit">
                @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending"   {{ old('status') == 'Pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="Confirmed" {{ old('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
                <textarea name="notes" rows="3" class="form-control"
                          placeholder="Additional notes...">{{ old('notes') }}</textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow">
                    <i class="bi bi-save me-1"></i> Book Appointment
                </button>
                <a href="{{ route('appointments.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection