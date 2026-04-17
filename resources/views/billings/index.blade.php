@extends('layouts.app')
@section('title', 'Billing')

@section('content')
<div class="page-header">
    <div>
        <h4>Billing</h4>
        <p>Manage patient billing records</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin', 'doctor']))
        <a href="{{ route('billings.create') }}" class="btn-careflow">
            <i class="bi bi-plus-lg me-1"></i> Add Billing
        </a>
    @endif
</div>

<div class="search-wrap">
    <form method="GET" action="{{ route('billings.index') }}" class="search-form">
        <i class="bi bi-search"></i>
        <input type="text" name="search"
               placeholder="Search by patient, doctor, description..."
               value="{{ $search ?? '' }}">
        @if(!empty($search))
            <a href="{{ route('billings.index') }}" class="search-clear">&times;</a>
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
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                @if(in_array(Auth::user()->role, ['admin', 'doctor']))
                    <th class="text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($billings as $billing)
                <tr>
                    <td>{{ $billings->firstItem() + $loop->index }}</td>
                    <td><strong>{{ $billing->patient->full_name }}</strong></td>
                    <td>Dr. {{ $billing->doctor->full_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($billing->billing_date)->format('M d, Y') }}</td>
                    <td>{{ $billing->description }}</td>
                    <td style="font-weight:600;color:#534AB7;">
                        ₱{{ number_format($billing->amount, 2) }}
                    </td>
                    <td>
                        <span class="badge-pill badge-{{ strtolower($billing->status) }}">
                            {{ $billing->status }}
                        </span>
                    </td>
                    @if(in_array(Auth::user()->role, ['admin', 'doctor']))
                        <td class="text-center">
                            <a href="{{ route('billings.edit', $billing) }}"
                               class="btn btn-sm btn-outline-careflow me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('billings.destroy', $billing) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this billing record?')">
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
                            No billing records found for "{{ $search }}"
                        @else
                            No billing records found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="border-top:1px solid #f0f0f0;">
        <span style="font-size:12px;color:#aaa;">
            Showing {{ $billings->firstItem() ?? 0 }} to {{ $billings->lastItem() ?? 0 }}
            of {{ $billings->total() }} records
        </span>
        {{ $billings->links() }}
    </div>
</div>
@endsection