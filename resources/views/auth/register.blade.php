<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account — Careflow</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --brand: #0d7f7a; --brand-2: #0a6b67; --brand-glow: rgba(13,127,122,.2); --bg: #f0f2f7; --surface: #fff; --border: #e3e6ef; --text: #0d1117; --text-2: #3d4554; --text-muted: #7c859a; --rose: #e11d48; --rose-soft: #ffe4ec; }
        .dark { --bg: #0b0d13; --surface: #13161f; --border: #252a38; --text: #edf0f7; --text-2: #b0b8cc; --text-muted: #606880; --brand: #14a89f; --rose-soft: rgba(225,29,72,.14); }
        body { font-family: 'DM Sans', system-ui, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; transition: background .18s, color .18s; }
        .auth-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 40px; width: 100%; max-width: 440px; box-shadow: 0 8px 32px rgba(0,0,0,.08); }
        .auth-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
        .auth-logo-icon { width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, var(--brand), #06b6d4); display: flex; align-items: center; justify-content: center; }
        .auth-logo-icon svg { width: 20px; height: 20px; color: #fff; }
        .auth-logo-name { font-size: 16px; font-weight: 700; color: var(--text); }
        .auth-logo-sub { font-size: 10.5px; color: var(--brand); font-weight: 500; text-transform: uppercase; letter-spacing: .5px; }
        h1 { font-size: 22px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
        .auth-sub { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-2); margin-bottom: 6px; }
        input[type=email], input[type=password], input[type=text] { width: 100%; padding: 10px 13px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 14px; font-family: inherit; outline: none; transition: border-color .15s, box-shadow .15s; }
        input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-glow); }
        .error-msg { color: var(--rose); font-size: 12px; margin-top: 5px; display: block; }
        .btn-submit { width: 100%; padding: 11px; background: var(--brand); color: #fff; border: none; border-radius: 8px; font-size: 14.5px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all .15s; margin-top: 8px; box-shadow: 0 2px 8px var(--brand-glow); }
        .btn-submit:hover { background: var(--brand-2); transform: translateY(-1px); }
        .auth-footer { text-align: center; margin-top: 20px; font-size: 13.5px; color: var(--text-muted); }
        .auth-footer a { color: var(--brand); font-weight: 600; }
        .auth-footer a:hover { text-decoration: underline; }
        .info-note { background: rgba(13,127,122,.08); border: 1px solid rgba(13,127,122,.2); border-radius: 8px; padding: 10px 14px; font-size: 12.5px; color: var(--brand); margin-bottom: 20px; }
        .dark-toggle { position: fixed; top: 16px; right: 16px; width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted); }
        .dark-toggle svg { width: 15px; height: 15px; }
        .back-link { position: fixed; top: 16px; left: 16px; font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 5px; text-decoration: none; }
        .back-link:hover { color: var(--brand); }
    </style>
</head>
<body>

<button class="dark-toggle" @click="darkMode = !darkMode">
    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
</button>

<a href="{{ route('landing') }}" class="back-link">← Back to home</a>

<div class="auth-card">
    <div class="auth-logo">
        <div class="auth-logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
        </div>
        <div>
            <div class="auth-logo-name">Careflow</div>
            <div class="auth-logo-sub">Clinic System</div>
        </div>
    </div>

    <h1>Create account</h1>
    <p class="auth-sub">Join Careflow and start managing your clinic</p>

    <div class="info-note">New accounts are assigned the <strong>Staff</strong> role by default. An admin can update your role after registration.</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Juan dela Cruz">
            @error('name') <span class="error-msg">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="you@clinic.com">
            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Min. 8 characters">
            @error('password') <span class="error-msg">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password">
        </div>
        <button type="submit" class="btn-submit">Create Account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</div>

</body>
</html>