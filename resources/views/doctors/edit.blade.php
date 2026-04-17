@extends('layouts.app')
@section('title', 'Edit Doctor')

@section('content')
<div class="page-header">
    <div>
        <h4>Edit Doctor</h4>
        <p>Update the doctor information below</p>
    </div>
    <a href="{{ route('doctors.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card">
    <form action="{{ route('doctors.update', $doctor) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control"
                       value="{{ old('first_name', $doctor->first_name) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control"
                       value="{{ old('last_name', $doctor->last_name) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Specialization</label>
                <input type="text" name="specialization" class="form-control"
                       value="{{ old('specialization', $doctor->specialization) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="{{ old('contact_number', $doctor->contact_number) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $doctor->email) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active"   {{ old('status', $doctor->status) == 'Active'   ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('status', $doctor->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn-careflow">
                    <i class="bi bi-save me-1"></i> Update Doctor
                </button>
                <a href="{{ route('doctors.index') }}" class="btn-outline-careflow">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection