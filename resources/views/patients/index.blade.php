@extends('layouts.app')
@section('title', 'Patients')

@section('content')
<div class="page-header">
    <div>
        <h4>Patients</h4>
        <p>Manage all patient records</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin', 'staff']))
        <a href="{{ route('patients.create') }}" class="btn-careflow">
            <i class="bi bi-plus-lg me-1"></i> Add Patient
        </a>
    @endif
</div>

<div class="search-wrap">
    <form method="GET" action="{{ route('patients.index') }}" class="search-form">
        <i class="bi bi-search"></i>
        <input type="text" name="search"
               placeholder="Search by name, contact, address..."
               value="{{ $search ?? '' }}">
        @if(!empty($search))
            <a href="{{ route('patients.index') }}" class="search-clear">&times;</a>
        @endif
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
                <th>Full Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Contact</th>
                <th>Address</th>
                @if(in_array(Auth::user()->role, ['admin', 'staff']))
                    <th class="text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td>{{ $patients->firstItem() + $loop->index }}</td>
                    <td><strong>{{ $patient->full_name }}</strong></td>
                    <td>{{ $patient->gender }}</td>
                    <td>{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}</td>
                    <td>{{ $patient->contact_number }}</td>
                    <td>{{ $patient->address }}</td>
                    @if(in_array(Auth::user()->role, ['admin', 'staff']))
                        <td class="text-center">
                            <a href="{{ route('patients.edit', $patient) }}"
                               class="btn btn-sm btn-outline-careflow me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('patients.destroy', $patient) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this patient?')">
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
                    <td colspan="7" class="text-center text-muted py-4">
                        @if(!empty($search))
                            No patients found for "{{ $search }}"
                        @else
                            No patients found.
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
</div>
@endsection