@extends('layouts.app')
@section('page-title', 'My Dashboard')

@section('content')
<div style="text-align:center;padding:80px 20px;">
    <div style="width:64px;height:64px;border-radius:50%;background:var(--amber-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;color:var(--amber);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
    </div>
    <h2 style="font-size:20px;font-weight:700;color:var(--text);margin-bottom:8px;">No Patient Profile Linked</h2>
    <p style="color:var(--text-muted);font-size:14px;max-width:380px;margin:0 auto;">Your account doesn't have a patient profile yet. Please contact the clinic staff to link your account to your patient record.</p>
</div>
@endsection