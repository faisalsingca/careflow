<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careflow — Clinic Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --brand: #0d7f7a; --brand-2: #0a6b67; --brand-glow: rgba(13,127,122,.2);
            --bg: #f0f2f7; --surface: #fff; --border: #e3e6ef;
            --text: #0d1117; --text-2: #3d4554; --text-muted: #7c859a;
            --emerald: #059669; --emerald-soft: #d1fae5;
            --sky: #0ea5e9; --sky-soft: #e0f5fe;
            --amber: #d97706; --amber-soft: #fef3c7;
            --violet: #7c3aed; --violet-soft: #ede9fe;
        }
        .dark {
            --bg: #0b0d13; --surface: #13161f; --border: #252a38;
            --text: #edf0f7; --text-2: #b0b8cc; --text-muted: #606880;
            --brand: #14a89f; --brand-glow: rgba(20,168,159,.2);
            --emerald-soft: rgba(5,150,105,.15); --sky-soft: rgba(14,165,233,.15);
            --amber-soft: rgba(217,119,6,.15); --violet-soft: rgba(124,58,237,.15);
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', system-ui, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; -webkit-font-smoothing: antialiased; transition: background .18s, color .18s; }
        a { text-decoration: none; color: inherit; }

        /* NAV */
        nav { position: fixed; top: 0; left: 0; right: 0; z-index: 50; background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 40px; height: 60px; display: flex; align-items: center; justify-content: space-between; backdrop-filter: blur(12px); }
        .nav-brand { display: flex; align-items: center; gap: 10px; }
        .nav-logo { width: 34px; height: 34px; border-radius: 9px; background: linear-gradient(135deg, var(--brand), #06b6d4); display: flex; align-items: center; justify-content: center; }
        .nav-logo svg { width: 18px; height: 18px; color: #fff; }
        .nav-brand-name { font-size: 15px; font-weight: 700; color: var(--text); letter-spacing: -.3px; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .btn-nav-outline { padding: 7px 16px; border-radius: 8px; border: 1px solid var(--border); background: transparent; color: var(--text-2); font-size: 13.5px; font-weight: 500; cursor: pointer; font-family: inherit; transition: all .15s; }
        .btn-nav-outline:hover { background: var(--bg); border-color: var(--brand); color: var(--brand); }
        .btn-nav-primary { padding: 7px 18px; border-radius: 8px; background: var(--brand); color: #fff; font-size: 13.5px; font-weight: 600; border: none; cursor: pointer; font-family: inherit; transition: all .15s; box-shadow: 0 2px 8px var(--brand-glow); }
        .btn-nav-primary:hover { background: var(--brand-2); transform: translateY(-1px); }
        .dark-toggle { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border); background: transparent; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted); transition: all .15s; }
        .dark-toggle:hover { background: var(--bg); color: var(--text); }
        .dark-toggle svg { width: 15px; height: 15px; }

        /* HERO */
        .hero { padding: 140px 40px 80px; text-align: center; max-width: 800px; margin: 0 auto; }
        .hero-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; border-radius: 20px; background: var(--emerald-soft); color: var(--emerald); font-size: 12px; font-weight: 600; margin-bottom: 24px; border: 1px solid rgba(5,150,105,.2); }
        .hero-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--emerald); }
        .hero h1 { font-size: clamp(36px, 6vw, 60px); font-weight: 700; color: var(--text); letter-spacing: -1.5px; line-height: 1.1; margin-bottom: 20px; }
        .hero h1 span { background: linear-gradient(135deg, var(--brand), #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero p { font-size: 17px; color: var(--text-muted); max-width: 560px; margin: 0 auto 36px; line-height: 1.7; }
        .hero-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-hero { padding: 13px 28px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all .15s; border: none; }
        .btn-hero-primary { background: var(--brand); color: #fff; box-shadow: 0 4px 16px var(--brand-glow); }
        .btn-hero-primary:hover { background: var(--brand-2); transform: translateY(-2px); box-shadow: 0 8px 24px var(--brand-glow); }
        .btn-hero-outline { background: transparent; border: 1.5px solid var(--border); color: var(--text-2); }
        .btn-hero-outline:hover { border-color: var(--brand); color: var(--brand); background: var(--surface); }

        /* FEATURES */
        .features { padding: 60px 40px; max-width: 1100px; margin: 0 auto; }
        .features-label { text-align: center; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--brand); margin-bottom: 12px; }
        .features h2 { text-align: center; font-size: 32px; font-weight: 700; color: var(--text); letter-spacing: -.6px; margin-bottom: 48px; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        @media (max-width: 768px) { .features-grid { grid-template-columns: 1fr; } }
        .feature-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 24px; transition: all .2s; }
        .feature-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); transform: translateY(-2px); }
        .feature-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
        .feature-icon svg { width: 20px; height: 20px; }
        .fi-emerald { background: var(--emerald-soft); color: var(--emerald); }
        .fi-sky { background: var(--sky-soft); color: var(--sky); }
        .fi-amber { background: var(--amber-soft); color: var(--amber); }
        .fi-violet { background: var(--violet-soft); color: var(--violet); }
        .fi-brand { background: rgba(13,127,122,.12); color: var(--brand); }
        .fi-rose { background: rgba(225,29,72,.1); color: #e11d48; }
        .feature-card h3 { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
        .feature-card p { font-size: 13.5px; color: var(--text-muted); line-height: 1.6; }

        /* STATS */
        .stats-section { background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 48px 40px; margin: 20px 0; }
        .stats-inner { max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: repeat(4,1fr); gap: 24px; text-align: center; }
        @media (max-width: 640px) { .stats-inner { grid-template-columns: repeat(2,1fr); } }
        .stat-num { font-size: 36px; font-weight: 700; color: var(--brand); letter-spacing: -1px; }
        .stat-lbl { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        /* ROLES */
        .roles-section { padding: 60px 40px; max-width: 900px; margin: 0 auto; }
        .roles-section h2 { text-align: center; font-size: 28px; font-weight: 700; color: var(--text); letter-spacing: -.5px; margin-bottom: 32px; }
        .roles-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; }
        @media (max-width:640px) { .roles-grid { grid-template-columns: 1fr; } }
        .role-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 20px; }
        .role-title { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .role-badge { padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .rb-admin { background: rgba(225,29,72,.1); color: #e11d48; }
        .rb-doctor { background: var(--emerald-soft); color: var(--emerald); }
        .rb-staff { background: var(--sky-soft); color: var(--sky); }
        .rb-viewer { background: rgba(100,116,139,.1); color: #64748b; }
        .role-perms { list-style: none; }
        .role-perms li { font-size: 13px; color: var(--text-muted); padding: 3px 0; display: flex; align-items: center; gap: 7px; }
        .role-perms li::before { content: '✓'; color: var(--brand); font-weight: 700; font-size: 12px; }

        /* CTA */
        .cta { padding: 80px 40px; text-align: center; }
        .cta-inner { max-width: 520px; margin: 0 auto; background: linear-gradient(135deg, var(--brand) 0%, #06b6d4 100%); border-radius: 20px; padding: 52px 40px; }
        .cta h2 { font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 12px; }
        .cta p { color: rgba(255,255,255,.8); font-size: 15px; margin-bottom: 28px; }
        .btn-cta { display: inline-flex; align-items: center; gap: 8px; padding: 13px 28px; background: #fff; color: var(--brand); border-radius: 10px; font-size: 15px; font-weight: 700; border: none; cursor: pointer; font-family: inherit; transition: all .15s; }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.15); }

        /* FOOTER */
        footer { border-top: 1px solid var(--border); padding: 24px 40px; text-align: center; color: var(--text-muted); font-size: 13px; }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">
        <div class="nav-logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
        </div>
        <span class="nav-brand-name">Careflow</span>
    </div>
    <div class="nav-actions">
        <button class="dark-toggle" @click="darkMode = !darkMode">
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
        </button>
        <a href="{{ route('login') }}"><button class="btn-nav-outline">Sign In</button></a>
        <a href="{{ route('register') }}"><button class="btn-nav-primary">Get Started</button></a>
    </div>
</nav>

<section class="hero">
    <div class="hero-badge">Clinic Management System</div>
    <h1>Healthcare, <span>Simplified</span><br>for your clinic</h1>
    <p>Careflow brings together patient records, appointments, billing, and medical records into one clean, modern platform built for healthcare teams.</p>
    <div class="hero-actions">
        <a href="{{ route('register') }}"><button class="btn-hero btn-hero-primary">Get Started Free</button></a>
        <a href="{{ route('login') }}"><button class="btn-hero btn-hero-outline">Sign In →</button></a>
    </div>
</section>

<div class="stats-section">
    <div class="stats-inner">
        <div><div class="stat-num">5+</div><div class="stat-lbl">Core Modules</div></div>
        <div><div class="stat-num">4</div><div class="stat-lbl">Role Levels</div></div>
        <div><div class="stat-num">100%</div><div class="stat-lbl">Web Based</div></div>
        <div><div class="stat-num">∞</div><div class="stat-lbl">Patient Records</div></div>
    </div>
</div>

<section class="features">
    <div class="features-label">What's Included</div>
    <h2>Everything your clinic needs</h2>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon fi-emerald">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" /></svg>
            </div>
            <h3>Patient Management</h3>
            <p>Register, search, and manage all patient records including demographics and contact info.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-sky">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25" /></svg>
            </div>
            <h3>Appointments</h3>
            <p>Schedule, confirm, and track appointments with real-time status updates and sorting.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-violet">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25" /></svg>
            </div>
            <h3>Medical Records</h3>
            <p>Store diagnoses, prescriptions, and clinical notes per patient visit securely.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-amber">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75" /></svg>
            </div>
            <h3>Billing</h3>
            <p>Track payments, generate billing records, and monitor paid vs unpaid status with ease.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-brand">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877" /></svg>
            </div>
            <h3>Doctor Management</h3>
            <p>Manage doctor profiles, specializations, and toggle active/inactive status.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-rose">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07" /></svg>
            </div>
            <h3>Role-Based Access</h3>
            <p>Control access with Admin, Doctor, Staff, and Viewer roles — each with the right permissions.</p>
        </div>
    </div>
</section>

<section class="roles-section">
    <h2>Role Permissions</h2>
    <div class="roles-grid">
        <div class="role-card">
            <div class="role-title"><span class="role-badge rb-admin">Admin</span> Full Access</div>
            <ul class="role-perms">
                <li>All modules — read, create, edit, delete</li>
                <li>Manage doctors and user roles</li>
                <li>Access billing and medical records</li>
                <li>View dashboard and reports</li>
            </ul>
        </div>
        <div class="role-card">
            <div class="role-title"><span class="role-badge rb-doctor">Doctor</span> Clinical Access</div>
            <ul class="role-perms">
                <li>View patients and appointments</li>
                <li>Create and edit medical records</li>
                <li>Create and manage billing records</li>
                <li>Cannot create or delete patients/appointments</li>
            </ul>
        </div>
        <div class="role-card">
            <div class="role-title"><span class="role-badge rb-staff">Staff</span> Operations</div>
            <ul class="role-perms">
                <li>Create, edit, delete patients</li>
                <li>Create, edit, delete appointments</li>
                <li>Cannot access medical records or billing</li>
                <li>Cannot manage doctors or users</li>
            </ul>
        </div>
        <div class="role-card">
            <div class="role-title"><span class="role-badge rb-viewer">Viewer</span> Read Only</div>
            <ul class="role-perms">
                <li>View dashboard</li>
                <li>View patients list</li>
                <li>View appointments list</li>
                <li>No create, edit, or delete access</li>
            </ul>
        </div>
    </div>
</section>

<section class="cta">
    <div class="cta-inner">
        <h2>Ready to get started?</h2>
        <p>Create your account and manage your clinic smarter today.</p>
        <a href="{{ route('register') }}"><button class="btn-cta">Create Free Account →</button></a>
    </div>
</section>

<footer>
    © {{ date('Y') }} Careflow Clinic System. Built with Laravel.
</footer>

</body>
</html>