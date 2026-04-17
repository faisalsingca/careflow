@extends('layouts.app')
@section('title', 'Doctors')

@section('content')
<div class="page-header">
    <div>
        <h4>Doctors</h4>
        <p>Manage clinic doctors and specialists</p>
    </div>
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('doctors.create') }}" class="btn-careflow">
            <i class="bi bi-plus-lg me-1"></i> Add Doctor
        </a>
    @endif
</div>

<div class="search-wrap">
    <form method="GET" action="{{ route('doctors.index') }}" class="search-form">
        <i class="bi bi-search"></i>
        <input type="text" name="search"
               placeholder="Search by name, specialization, email..."
               value="{{ $search ?? '' }}">
        @if(!empty($search))
            <a href="{{ route('doctors.index') }}" class="search-clear">&times;</a>
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
                <th>Specialization</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Status</th>
                @if(Auth::user()->role === 'admin')
                    <th class="text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($doctors as $doctor)
                <tr>
                    <td>{{ $doctors->firstItem() + $loop->index }}</td>
                    <td><strong>Dr. {{ $doctor->full_name }}</strong></td>
                    <td>{{ $doctor->specialization }}</td>
                    <td>{{ $doctor->contact_number }}</td>
                    <td>{{ $doctor->email }}</td>
                    <td>
                        <span class="badge-pill badge-{{ strtolower($doctor->status) }}">
                            {{ $doctor->status }}
                        </span>
                    </td>
                    @if(Auth::user()->role === 'admin')
                        <td class="text-center">
                            <a href="{{ route('doctors.edit', $doctor) }}"
                               class="btn btn-sm btn-outline-careflow me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('doctors.destroy', $doctor) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this doctor?')">
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
                            No doctors found for "{{ $search }}"
                        @else
                            No doctors found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="border-top:1px solid #f0f0f0;">
        <span style="font-size:12px;color:#aaa;">
            Showing {{ $doctors->firstItem() ?? 0 }} to {{ $doctors->lastItem() ?? 0 }}
            of {{ $doctors->total() }} doctors
        </span>
        {{ $doctors->links() }}
    </div>
</div>
@endsection