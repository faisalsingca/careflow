@extends('layouts.app')
@section('title', 'Access Denied')

@section('content')
<div class="d-flex justify-content-center align-items-center"
     style="min-height: 60vh;">
    <div class="text-center">
        <div style="font-size:80px;margin-bottom:16px;">🔒</div>
        <h2 style="font-size:28px;font-weight:700;color:#534AB7;margin-bottom:8px;">
            Access Denied
        </h2>
        <p style="color:#aaa;font-size:14px;max-width:400px;margin:0 auto 24px;">
            You don't have permission to access this page.
            This area is restricted based on your role.
        </p>
        <div style="background:#F3F1FF;border-radius:12px;padding:16px 24px;
                    display:inline-block;margin-bottom:24px;">
            <span style="font-size:13px;color:#534AB7;">
                <i class="bi bi-shield-fill-check me-2"></i>
                Your role: <strong>{{ ucfirst(Auth::user()->role) }}</strong>
            </span>
        </div>
        <br>
        <a href="{{ route('dashboard') }}" class="btn-careflow"
           style="text-decoration:none;display:inline-block;">
            <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection