@extends('layouts.app')
@section('title', 'Appointments')

@section('content')
<div class="page-header">
    <div>
        <h4>Appointments</h4>
        <p>Manage patient appointments</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin', 'staff']))
        <a href="{{ route('appointments.create') }}" class="btn-careflow">
            <i class="bi bi-plus-lg me-1"></i> Book Appointment
        </a>
    @endif
</div>

<div class="search-wrap">
    <form method="GET" action="{{ route('appointments.index') }}" class="search-form">
        <i class="bi bi-search"></i>
        <input type="text" name="search"
               placeholder="Search by patient, doctor, reason..."
               value="{{ $search ?? '' }}">
        @if(!empty($search))
            <a href="{{ route('appointments.index') }}" class="search-clear">&times;</a>
        @endif
        <input type="hidden" name="sort" value="{{ $sort ?? 'date_desc' }}">
    </form>
    <form method="GET" action="{{ route('appointments.index') }}">
        <input type="hidden" name="search" value="{{ $search ?? '' }}">
        <select name="sort" class="sort-select" onchange="this.form.submit()">
            <option value="date_desc" {{ ($sort ?? 'date_desc') === 'date_desc' ? 'selected' : '' }}>Date (Newest)</option>
            <option value="date_asc"  {{ ($sort ?? '') === 'date_asc'  ? 'selected' : '' }}>Date (Oldest)</option>
            <option value="time_asc"  {{ ($sort ?? '') === 'time_asc'  ? 'selected' : '' }}>Time (Earliest)</option>
            <option value="time_desc" {{ ($sort ?? '') === 'time_desc' ? 'selected' : '' }}>Time (Latest)</option>
        </select>
    </form>
    @if(!empty($search))
        <span style="font-size:12px;color:#aaa;">
            Results for <strong style="color:#534AB7;">"{{ $search }}"</strong>
        </span>
    @endif
</div>

<div class="panel">
    <table class="clinic-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reason</th>
                <th>Status</th>
                @if(in_array(Auth::user()->role, ['admin', 'staff']))
                    <th class="text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointments->firstItem() + $loop->index }}</td>
                    <td><strong>{{ $appointment->patient->full_name }}</strong></td>
                    <td>Dr. {{ $appointment->doctor->full_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                    <td style="color:#7F77DD;font-weight:600;">
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                    </td>
                    <td>{{ $appointment->reason }}</td>
                    <td>
                        <span class="badge-pill badge-{{ strtolower($appointment->status) }}">
                            {{ $appointment->status }}
                        </span>
                    </td>
                    @if(in_array(Auth::user()->role, ['admin', 'staff']))
                        <td class="text-center">
                            <a href="{{ route('appointments.edit', $appointment) }}"
                               class="btn btn-sm btn-outline-careflow me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('appointments.destroy', $appointment) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Cancel this appointment?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm"
                                        style="background:#fce4ec;color:#c62828;border-radius:8px;border:none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        @if(!empty($search))
                            No appointments found for "{{ $search }}"
                        @else
                            No appointments found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="border-top:1px solid #f0f0f0;">
        <span style="font-size:12px;color:#aaa;">
            Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }}
            of {{ $appointments->total() }} appointments
        </span>
        {{ $appointments->links() }}
    </div>
</div>
@endsection