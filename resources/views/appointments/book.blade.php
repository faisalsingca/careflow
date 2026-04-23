@extends('layouts.app')
@section('page-title', 'Book Appointment')

@section('content')
<div class="page-header">
    <div>
        <h4>Book an Appointment</h4>
        <p>Submit your appointment request — staff will confirm and assign a doctor</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card" style="max-width:560px;">
    <form action="{{ route('appointments.book.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Preferred Date</label>
                <input type="date" name="appointment_date"
                       class="form-control @error('appointment_date') is-invalid @enderror"
                       value="{{ old('appointment_date') }}"
                       min="{{ date('Y-m-d') }}">
                @error('appointment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Preferred Time</label>
                <input type="time" name="appointment_time"
                       class="form-control @error('appointment_time') is-invalid @enderror"
                       value="{{ old('appointment_time') }}">
                @error('appointment_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Reason for Visit</label>
                <input type="text" name="reason"
                       class="form-control @error('reason') is-invalid @enderror"
                       value="{{ old('reason') }}" placeholder="Describe your concern...">
                @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <div style="background:var(--amber-soft);border:1px solid var(--amber);border-radius:var(--r-sm);padding:12px 16px;font-size:13px;color:var(--amber);display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-info-circle"></i>
                    Your appointment will be <strong>Pending</strong> until approved by staff. A doctor will be assigned for you.
                </div>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow">
                    <i class="bi bi-calendar-plus me-1"></i> Submit Request
                </button>
                <a href="{{ route('appointments.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection