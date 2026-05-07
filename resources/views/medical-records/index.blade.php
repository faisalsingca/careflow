@extends('layouts.app')
@section('title', 'Medical Records')

@section('content')
<div class="page-header">
    <div>
        <h4>Medical Records</h4>
        <p>Patient diagnosis and prescription records</p>
    </div>
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('medical-records.create') }}" class="btn-careflow">
            <i class="bi bi-plus-lg me-1"></i> Add Record
        </a>
    @endif
</div>

<div class="search-wrap">
    <form method="GET" action="{{ route('medical-records.index') }}" class="search-form">
        <i class="bi bi-search"></i>
        <input type="text" name="search"
               placeholder="Search by patient, doctor, diagnosis..."
               value="{{ $search ?? '' }}">
        @if(!empty($search))
            <a href="{{ route('medical-records.index') }}" class="search-clear">&times;</a>
        @endif
    </form>
    @if(!empty($search))
        <span style="font-size:12px;color:#aaa;">
            Results for <strong style="color:#534AB7;">"{{ $search }}"</strong>
        </span>
    @endif
</div>

<div class="panel">
    @if($isDoctorView)
        <div class="d-flex justify-content-between align-items-center px-4 pt-4 pb-2">
            <div>
                <h5 class="mb-1" style="font-weight:700;">Your Patients</h5>
                <p class="mb-0" style="font-size:13px;color:#7a7a7a;">Click a patient to view their full visit history.</p>
            </div>
        </div>

        <table class="clinic-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Visits</th>
                    <th>Latest Visit</th>
                    <th>Latest Diagnosis</th>
                    <th>Latest Prescription</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    @php
                        $latestRecord = $patient->medicalRecords->first();
                    @endphp
                    <tr>
                        <td>{{ $patients->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $patient->full_name }}</strong></td>
                        <td>{{ $patient->doctor_visit_count }}</td>
                        <td>{{ $latestRecord ? \Carbon\Carbon::parse($latestRecord->record_date)->format('M d, Y') : '—' }}</td>
                        <td>{{ $latestRecord?->diagnosis ?? 'Pending doctor update' }}</td>
                        <td>{{ $latestRecord?->prescription ?? 'None' }}</td>
                        <td class="text-center">
                            <a href="{{ route('medical-records.patient-history', $patient) }}" class="btn btn-sm btn-outline-careflow">
                                <i class="bi bi-journal-text me-1"></i> View History
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            @if(!empty($search))
                                No patients found for "{{ $search }}"
                            @else
                                No patients with medical records found.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top:1px solid #f0f0f0;">
            <span style="font-size:12px;color:#aaa;">
                Showing {{ $patients->firstItem() ?? 0 }} to {{ $patients->lastItem() ?? 0 }}
                of {{ $patients->total() }} patients
            </span>
            {{ $patients->links() }}
        </div>
    @else
        <table class="clinic-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Diagnosis</th>
                    <th>Prescription</th>
                    @if(in_array(Auth::user()->role, ['admin', 'doctor']))
                        <th class="text-center">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $records->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $record->patient->full_name }}</strong></td>
                        <td>Dr. {{ $record->doctor->full_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}</td>
                        <td>{{ $record->diagnosis ?? 'Pending doctor update' }}</td>
                        <td>{{ $record->prescription ?? 'None' }}</td>
                        @if(in_array(Auth::user()->role, ['admin', 'doctor']))
                            <td class="text-center">
                                <a href="{{ route('medical-records.edit', $record) }}"
                                   class="btn btn-sm btn-outline-careflow me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('medical-records.destroy', $record) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm"
                                                style="background:#fce4ec;color:#c62828;border-radius:8px;border:none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            @if(!empty($search))
                                No records found for "{{ $search }}"
                            @else
                                No medical records found.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top:1px solid #f0f0f0;">
            <span style="font-size:12px;color:#aaa;">
                Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }}
                of {{ $records->total() }} records
            </span>
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
