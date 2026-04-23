@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Welcome back, {{ Auth::user()?->name ?? 'Guest' }}. Here's what's happening today.</p>
</div>

{{-- ── STAT CARDS ────────────────────────────────────────────── --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-icon icon-blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>
        <div class="stat-card-body">
            <span class="stat-card-label">Total Patients</span>
            <span class="stat-card-value">{{ number_format($totalPatients) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon icon-green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <div class="stat-card-body">
            <span class="stat-card-label">Total Doctors</span>
            <span class="stat-card-value">{{ number_format($totalDoctors) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon icon-yellow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
        </div>
        <div class="stat-card-body">
            <span class="stat-card-label">Today's Appointments</span>
            <span class="stat-card-value">{{ number_format($todayAppointments) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon icon-purple">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>
        </div>
        <div class="stat-card-body">
            <span class="stat-card-label">Total Revenue</span>
            <span class="stat-card-value">₱{{ number_format($totalRevenue, 0) }}</span>
        </div>
    </div>

</div>

{{-- ── DONUT CHART: Male / Female Distribution ─────────────────── --}}
<div class="mb-6">
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Patient Gender Distribution</span>
            <span class="text-muted">{{ $totalPatients }} total</span>
        </div>
        <div class="panel-body">
            @php
                $malePercent = $totalPatients > 0 ? round(($malePatients / $totalPatients) * 100) : 0;
                $femalePercent = $totalPatients > 0 ? round(($femalePatients / $totalPatients) * 100) : 0;
                $otherPercent = 100 - $malePercent - $femalePercent;
                // Conic gradient for donut
                $maleDeg = $malePercent * 3.6;
                $femaleDeg = $femalePercent * 3.6;
                $otherDeg = $otherPercent * 3.6;
                $conicGradient = "conic-gradient(from 0deg, #0d7f7a 0deg, #0d7f7a {$maleDeg}deg, #f59e0b {$maleDeg}deg, #f59e0b " . ($maleDeg + $femaleDeg) . "deg, #e2e8f0 " . ($maleDeg + $femaleDeg) . "deg, #e2e8f0 360deg)";
            @endphp

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: center;">
                {{-- Donut Chart --}}
                <div style="text-align: center;">
                    <div style="position: relative; width: 160px; height: 160px; margin: 0 auto;">
                        <div style="width: 100%; height: 100%; border-radius: 50%; background: {{ $conicGradient }}; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></div>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--surface); width: 80px; height: 80px; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);">
                            <span style="font-size: 24px; font-weight: 800; color: var(--text);">{{ $totalPatients }}</span>
                            <span style="font-size: 10px; color: var(--text-muted);">Total</span>
                        </div>
                    </div>
                </div>

                {{-- Legend & percentages --}}
                <div>
                    <div style="margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #0d7f7a;"></div>
                            <span style="flex: 1; font-weight: 500;">Male</span>
                            <span style="font-weight: 700; color: var(--text);">{{ $malePatients }}</span>
                            <span style="color: var(--text-muted);">({{ $malePercent }}%)</span>
                        </div>
                        <div class="gender-bar-track" style="background: var(--surface-3);">
                            <div class="gender-bar-fill" style="width: {{ $malePercent }}%; background: #0d7f7a;"></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b;"></div>
                            <span style="flex: 1; font-weight: 500;">Female</span>
                            <span style="font-weight: 700; color: var(--text);">{{ $femalePatients }}</span>
                            <span style="color: var(--text-muted);">({{ $femalePercent }}%)</span>
                        </div>
                        <div class="gender-bar-track" style="background: var(--surface-3);">
                            <div class="gender-bar-fill" style="width: {{ $femalePercent }}%; background: #f59e0b;"></div>
                        </div>
                    </div>
                    @if($otherPercent > 0)
                    <div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #e2e8f0;"></div>
                            <span style="flex: 1; font-weight: 500;">Other</span>
                            <span style="font-weight: 700; color: var(--text);">{{ $totalPatients - $malePatients - $femalePatients }}</span>
                            <span style="color: var(--text-muted);">({{ $otherPercent }}%)</span>
                        </div>
                        <div class="gender-bar-track" style="background: var(--surface-3);">
                            <div class="gender-bar-fill" style="width: {{ $otherPercent }}%; background: #e2e8f0;"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── BOTTOM: Two panels ────────────────────────────────────── --}}
<div class="grid-2">

    {{-- Recent Patients --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Recent Patients</span>
            <a href="{{ route('patients.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        @if($recentPatients->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" /></svg>
                <p>No patients yet.</p>
            </div>
        @else
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPatients as $patient)
                    <tr>
                        <td>
                            <a href="{{ route('patients.edit', $patient) }}" style="font-weight:600; color:var(--primary); text-decoration:none;">
                                {{ $patient->first_name }} {{ $patient->last_name }}
                            </a>
                        </td>
                        <td>
                            @if($patient->gender === 'Male')
                                <span class="badge badge-blue">Male</span>
                            @elseif($patient->gender === 'Female')
                                <span class="badge badge-purple">Female</span>
                            @else
                                <span class="badge badge-gray">Other</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);">{{ $patient->contact_number }}</td>
                        <td style="color:var(--text-muted);">{{ $patient->created_at->format('M j, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Today's Appointments --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Today's Appointments</span>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        @if($todayAppts->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25" /></svg>
                <p>No appointments scheduled for today.</p>
            </div>
        @else
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayAppts as $appt)
                    <tr>
                        <td style="font-weight:600;">
                            {{ $appt->patient->first_name }} {{ $appt->patient->last_name }}
                        </td>
                        <td style="color:var(--text-muted);">
                            Dr. {{ $appt->doctor->last_name }}
                        </td>
                        <td style="color:var(--text-muted);">
                            {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'Pending'   => 'badge-yellow',
                                    'Confirmed' => 'badge-blue',
                                    'Completed' => 'badge-green',
                                    'Cancelled' => 'badge-red',
                                ];
                                $badgeClass = $statusMap[$appt->status] ?? 'badge-gray';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $appt->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection