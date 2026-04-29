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
                @php
                    $selectedPatient = $patients->firstWhere('id', (int) old('patient_id'));
                @endphp
                <div class="patient-search" data-patient-search>
                    <input type="hidden" name="patient_id" value="{{ old('patient_id') }}" data-patient-id>
                    <input type="text"
                           class="form-control @error('patient_id') is-invalid @enderror"
                           placeholder="Search patient..."
                           autocomplete="off"
                           value="{{ $selectedPatient?->full_name }}"
                           data-patient-input>
                    <div class="patient-search-menu" data-patient-menu>
                        @foreach($patients as $patient)
                            <button type="button"
                                    class="patient-search-option"
                                    data-patient-option
                                    data-id="{{ $patient->id }}"
                                    data-name="{{ $patient->full_name }}">
                                {{ $patient->full_name }}
                            </button>
                        @endforeach
                    </div>
                </div>
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
<style>
    .patient-search { position: relative; }
    .patient-search-menu {
        display: none;
        position: absolute;
        z-index: 20;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        max-height: 220px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, .12);
        padding: 6px;
    }
    .patient-search.open .patient-search-menu { display: block; }
    .patient-search-option {
        width: 100%;
        border: 0;
        background: transparent;
        border-radius: 6px;
        padding: 9px 10px;
        text-align: left;
        color: var(--text, #1f2937);
    }
    .patient-search-option:hover,
    .patient-search-option:focus { background: #f4f2ff; outline: none; }
    .patient-search-option[hidden] { display: none; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.querySelector('[data-patient-search]');
        if (!wrapper) return;

        const input = wrapper.querySelector('[data-patient-input]');
        const hidden = wrapper.querySelector('[data-patient-id]');
        const options = Array.from(wrapper.querySelectorAll('[data-patient-option]'));

        const filterOptions = function () {
            const term = input.value.trim().toLowerCase();
            let visible = 0;

            options.forEach(function (option) {
                const matches = option.dataset.name.toLowerCase().includes(term);
                option.hidden = !matches;
                if (matches) visible++;
            });

            wrapper.classList.add('open');
        };

        input.addEventListener('focus', filterOptions);
        input.addEventListener('input', function () {
            hidden.value = '';
            filterOptions();
        });

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                hidden.value = option.dataset.id;
                input.value = option.dataset.name;
                wrapper.classList.remove('open');
            });
        });

        document.addEventListener('click', function (event) {
            if (!wrapper.contains(event.target)) {
                wrapper.classList.remove('open');
            }
        });
    });
</script>
@endsection
