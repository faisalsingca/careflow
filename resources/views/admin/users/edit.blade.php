@extends('layouts.app')
@section('page-title', 'Edit User Role')

@section('content')
<div class="page-header">
    <div>
        <h4>Edit User Role</h4>
        <p>Change the role for {{ $user->name }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="form-card" style="max-width:520px;">
    <div style="margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">USER</div>
        <div style="font-size:16px;font-weight:700;color:var(--text);">{{ $user->name }}</div>
        <div style="font-size:13px;color:var(--text-muted);">{{ $user->email }}</div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Assign Role</label>
            <select name="role" class="form-select @error('role') is-invalid @enderror">
                <option value="admin"   {{ $user->role == 'admin'   ? 'selected' : '' }}>Admin — Full access</option>
                <option value="doctor"  {{ $user->role == 'doctor'  ? 'selected' : '' }}>Doctor — Clinical access (view-only patients/appointments)</option>
                <option value="staff"   {{ $user->role == 'staff'   ? 'selected' : '' }}>Staff — Manage patients & appointments</option>
                <option value="patient" {{ $user->role == 'patient' ? 'selected' : '' }}>Patient — Book appointments, view own records</option>
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--r-sm);padding:14px;margin-top:4px;margin-bottom:20px;font-size:12.5px;color:var(--text-muted);">
            <strong style="color:var(--text-2);">Note:</strong> If assigning the <strong>Patient</strong> role, make sure this user's account is also linked to a patient record in the Patients module (via the user_id field).
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn-careflow"><i class="bi bi-save me-1"></i> Update Role</button>
            <a href="{{ route('admin.users.index') }}" class="btn-outline-careflow">Cancel</a>
        </div>
    </form>
</div>
@endsection