@extends('layouts.app')
@section('title', 'Patient History')

@section('content')
<div class="page-header">
    <div>
        <h4>{{ $patient->full_name }} - History</h4>
        <p>All medical records for this patient under the current doctor context</p>
    </div>
    <a href="{{ route('medical-records.index') }}" class="btn-outline-careflow">
        <i class="bi bi-arrow-left me-1"></i> Back to Patients
    </a>
</div>

<div class="panel mb-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center px-4 py-4">
        <div>
            <h5 class="mb-1" style="font-weight:700;">{{ $patient->full_name }}</h5>
            <p class="mb-0" style="color:#6b7280;">{{ $records->count() }} visit{{ $records->count() === 1 ? '' : 's' }} found</p>
        </div>
        <div class="text-end">
            <div style="font-size:12px;color:#9ca3af;">Most recent visit</div>
            <div style="font-weight:600;">
                {{ $records->first() ? \Carbon\Carbon::parse($records->first()->record_date)->format('M d, Y') : 'No records yet' }}
            </div>
        </div>
    </div>
</div>

<div class="panel">
    @if($records->isEmpty())
        <div class="text-center py-5 text-muted">
            No medical history available for this patient.
        </div>
    @else
        <table class="clinic-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Diagnosis</th>
                    <th>Prescription</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}</td>
                        <td>{{ $record->diagnosis ?? 'Pending doctor update' }}</td>
                        <td>{{ $record->prescription ?? 'None' }}</td>
                        <td>{{ $record->notes ?? 'No notes provided' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection