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
        <input type="text" name="search" placeholder="Search by name, contact, address..." value="{{ $search ?? '' }}">
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
                <th>Linked User</th>
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
                            <!-- Edit Button with Pen Icon -->
                            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <!-- Delete Button with Trash Icon -->
                            <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this patient?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>
    @if($patient->user)
        <span class="badge badge-brand">{{ $patient->user->name }}</span>
    @else
        @if(Auth::user()->role === 'admin')
            <form action="{{ route('patients.link-user', $patient) }}" method="POST" style="display:flex;gap:6px;align-items:center;">
                @csrf
                <select name="user_id" class="form-select" style="padding:4px 8px;font-size:12px;width:auto;">
                    <option value="">-- Link User --</option>
                    @foreach(\App\Models\User::where('role','patient')->whereDoesntHave('patient')->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Link</button>
            </form>
        @else
            <span style="color:var(--text-muted);font-size:12px;">Not linked</span>
        @endif
    @endif
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
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #f0f0f0;">
        <span style="font-size:12px;color:#aaa;">
            Showing {{ $patients->firstItem() ?? 0 }} to {{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} patients
        </span>
        {{ $patients->links() }}
    </div>
</div>
@endsection