@extends('layouts.app')
@section('page-title', 'My Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h4>Welcome, {{ $patient->full_name }}</h4>
        <p>Your personal health overview</p>
    </div>
    <a href="{{ route('appointments.book') }}" class="btn-careflow">
        <i class="bi bi-calendar-plus me-1"></i> Book Appointment
    </a>
</div>

{{-- Stat Cards --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-card-icon icon-blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25" /></svg>
        </div>
        <div class="stat-card-body">
            <span class="stat-card-label">Total Appointments</span>
            <span class="stat-card-value">{{ $appointments->count() }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon icon-yellow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
        </div>
        <div class="stat-card-body">
            <span class="stat-card-label">Pending</span>
            <span class="stat-card-value">{{ $appointments->where('status', 'Pending')->count() }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon icon-green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
        </div>
        <div class="stat-card-body">
            <span class="stat-card-label">Confirmed</span>
            <span class="stat-card-value">{{ $appointments->where('status', 'Confirmed')->count() }}</span>
        </div>
    </div>
</div>

{{-- Appointments --}}
<div class="panel" style="margin-bottom:20px;">
    <div class="panel-header">
        <span class="panel-title">My Appointments</span>
        <a href="{{ route('appointments.book') }}" class="btn btn-sm btn-outline">+ Book New</a>
    </div>
    @if($appointments->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5" /></svg>
            <p>No appointments yet. <a href="{{ route('appointments.book') }}" style="color:var(--brand);">Book one now</a></p>
        </div>
    @else
        <table class="clinic-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Doctor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</td>
                    <td>{{ $appt->reason }}</td>
                    <td>
                        @if($appt->doctor)
                            Dr. {{ $appt->doctor->full_name }}
                        @else
                            <span style="color:var(--text-muted);font-style:italic;">To be assigned</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $map = ['Pending'=>'badge-pending','Confirmed'=>'badge-confirmed','Completed'=>'badge-completed','Cancelled'=>'badge-cancelled'];
                        @endphp
                        <span class="badge {{ $map[$appt->status] ?? 'badge-gray' }}">{{ $appt->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Medical Records + Billing side by side --}}
<div class="grid-2">
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">My Medical Records</span>
        </div>
        @if($medicalRecords->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5" /></svg>
                <p>No medical records yet.</p>
            </div>
        @else
            <table class="clinic-table">
                <thead><tr><th>Date</th><th>Diagnosis</th><th>Doctor</th></tr></thead>
                <tbody>
                    @foreach($medicalRecords as $rec)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($rec->record_date)->format('M d, Y') }}</td>
                        <td>{{ $rec->diagnosis }}</td>
                        <td>Dr. {{ $rec->doctor->full_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">My Billing</span>
        </div>
        @if($billings->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101" /></svg>
                <p>No billing records yet.</p>
            </div>
        @else
            <table class="clinic-table">
                <thead><tr><th>Date</th><th>Description</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($billings as $bill)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($bill->billing_date)->format('M d, Y') }}</td>
                        <td>{{ $bill->description }}</td>
                        <td style="font-weight:600;">₱{{ number_format($bill->amount, 2) }}</td>
                        <td><span class="badge badge-{{ strtolower($bill->status) }}">{{ $bill->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection