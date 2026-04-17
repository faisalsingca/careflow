<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: true }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'Careflow') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           TOKENS
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w:   252px;
            --topbar-h:    58px;
            --r:           10px;
            --r-sm:        7px;
            --ease:        cubic-bezier(.4,0,.2,1);
            --dur:         180ms;

            /* Palette — light */
            --bg:          #f0f2f7;
            --surface:     #ffffff;
            --surface-2:   #f7f8fb;
            --surface-3:   #eef0f6;
            --border:      #e3e6ef;
            --border-2:    #d0d5e2;
            --text:        #0d1117;
            --text-2:      #3d4554;
            --text-muted:  #7c859a;

            /* Brand — deep teal */
            --brand:       #0d7f7a;
            --brand-2:     #0a6b67;
            --brand-glow:  rgba(13,127,122,.14);
            --brand-soft:  #e8f7f6;

            /* Accents */
            --sky:         #0ea5e9;
            --sky-soft:    #e0f5fe;
            --amber:       #d97706;
            --amber-soft:  #fef3c7;
            --rose:        #e11d48;
            --rose-soft:   #ffe4ec;
            --violet:      #7c3aed;
            --violet-soft: #ede9fe;
            --emerald:     #059669;
            --emerald-soft:#d1fae5;
            --slate:       #64748b;
            --slate-soft:  #f1f5f9;

            /* Shadows */
            --shadow-sm:   0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:   0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
            --shadow-lg:   0 10px 30px rgba(0,0,0,.10), 0 4px 8px rgba(0,0,0,.04);
            --shadow-brand:0 4px 16px rgba(13,127,122,.25);
        }

        .dark {
            --bg:          #0b0d13;
            --surface:     #13161f;
            --surface-2:   #191d28;
            --surface-3:   #1f2435;
            --border:      #252a38;
            --border-2:    #2e3447;
            --text:        #edf0f7;
            --text-2:      #b0b8cc;
            --text-muted:  #606880;

            --brand:       #14a89f;
            --brand-2:     #0d9490;
            --brand-glow:  rgba(20,168,159,.18);
            --brand-soft:  rgba(20,168,159,.12);

            --sky-soft:    rgba(14,165,233,.14);
            --amber-soft:  rgba(217,119,6,.14);
            --rose-soft:   rgba(225,29,72,.14);
            --violet-soft: rgba(124,58,237,.14);
            --emerald-soft:rgba(5,150,105,.14);
            --slate-soft:  rgba(100,116,139,.14);

            --shadow-sm:   0 1px 3px rgba(0,0,0,.3);
            --shadow-md:   0 4px 12px rgba(0,0,0,.4);
            --shadow-lg:   0 10px 30px rgba(0,0,0,.5);
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           BASE
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            transition: background var(--dur) var(--ease), color var(--dur) var(--ease);
        }

        a { color: inherit; text-decoration: none; }
        button { font-family: inherit; cursor: pointer; }
        input, select, textarea { font-family: inherit; }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           LAYOUT
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .app-shell { display: flex; min-height: 100vh; }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           SIDEBAR
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 50;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            transition: background var(--dur) var(--ease), border-color var(--dur) var(--ease);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand), #06b6d4);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 20px 18px 16px;
            border-bottom: 1px solid var(--border);
        }

        .brand-mark {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), #06b6d4);
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-brand);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        .brand-mark::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.2) 0%, transparent 60%);
        }
        .brand-mark svg { width: 19px; height: 19px; color: #fff; position: relative; z-index: 1; }

        .brand-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.1;
        }
        .brand-sub {
            font-size: 10.5px;
            font-weight: 500;
            color: var(--brand);
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .nav-section { padding: 18px 10px 6px; }

        .nav-section-label {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            padding: 0 8px;
            margin-bottom: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8.5px 10px;
            border-radius: var(--r-sm);
            color: var(--text-muted);
            font-size: 13.5px;
            font-weight: 500;
            transition: background var(--dur) var(--ease), color var(--dur) var(--ease);
            margin-bottom: 1px;
            position: relative;
        }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .nav-item:hover {
            background: var(--surface-2);
            color: var(--text-2);
        }

        .nav-item.active {
            background: var(--brand-soft);
            color: var(--brand);
            font-weight: 600;
        }
        .dark .nav-item.active { background: var(--brand-soft); color: var(--brand); }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 25%; bottom: 25%;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--brand);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 12px 10px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: var(--r-sm);
            margin-bottom: 8px;
        }

        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), #06b6d4);
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 8px var(--brand-glow);
        }

        .user-info { flex: 1; min-width: 0; }
        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .user-role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: capitalize;
            margin-top: 1px;
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px;
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            font-size: 12.5px;
            font-weight: 500;
            transition: all var(--dur) var(--ease);
        }
        .logout-btn svg { width: 14px; height: 14px; }
        .logout-btn:hover {
            background: var(--rose-soft);
            border-color: var(--rose);
            color: var(--rose);
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           TOPBAR
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 40;
            transition: background var(--dur) var(--ease), border-color var(--dur) var(--ease);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .topbar-left { display: flex; align-items: center; gap: 10px; }

        .topbar-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .topbar-sep {
            width: 1px; height: 16px;
            background: var(--border-2);
        }

        .topbar-date {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .topbar-right { display: flex; align-items: center; gap: 8px; }

        .role-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            background: var(--brand-soft);
            color: var(--brand);
            border: 1px solid rgba(13,127,122,.15);
            text-transform: capitalize;
            letter-spacing: 0.2px;
        }
        .role-chip::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--brand);
        }

        .icon-btn {
            width: 34px; height: 34px;
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            transition: all var(--dur) var(--ease);
        }
        .icon-btn svg { width: 16px; height: 16px; }
        .icon-btn:hover {
            background: var(--surface-2);
            color: var(--text);
            border-color: var(--border-2);
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           MAIN
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            flex: 1;
            min-width: 0;
        }

        .main-content {
            padding: 28px;
            max-width: 1380px;
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           PAGE HEADER
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .page-header {
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-header h1, .page-header h4 {
            font-size: 21px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.2;
        }
        .page-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 3px;
        }
        .page-header-actions { display: flex; align-items: center; gap: 8px; }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           STAT CARDS
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }
        @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 600px)  { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow var(--dur) var(--ease), transform var(--dur) var(--ease);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            opacity: 0;
            transition: opacity var(--dur) var(--ease);
        }
        .stat-card:hover::before { opacity: 1; }
        .stat-card.c-sky::before     { background: var(--sky); }
        .stat-card.c-brand::before   { background: var(--brand); }
        .stat-card.c-amber::before   { background: var(--amber); }
        .stat-card.c-violet::before  { background: var(--violet); }
        .stat-card.c-rose::before    { background: var(--rose); }
        .stat-card.c-emerald::before { background: var(--emerald); }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg { width: 20px; height: 20px; }

        .si-sky     { background: var(--sky-soft);     color: var(--sky); }
        .si-brand   { background: var(--brand-soft);   color: var(--brand); }
        .si-amber   { background: var(--amber-soft);   color: var(--amber); }
        .si-violet  { background: var(--violet-soft);  color: var(--violet); }
        .si-rose    { background: var(--rose-soft);    color: var(--rose); }
        .si-emerald { background: var(--emerald-soft); color: var(--emerald); }
        .si-slate   { background: var(--slate-soft);   color: var(--slate); }

        .stat-body { flex: 1; min-width: 0; }

        .stat-label {
            font-size: 11.5px;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: block;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 27px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.8px;
            line-height: 1;
            display: block;
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           PANEL
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            overflow: hidden;
            transition: border-color var(--dur) var(--ease);
        }

        .panel-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .panel-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.2px;
        }

        .panel-body { padding: 20px; }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           GRID HELPERS
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
        @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           TABLE
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .clinic-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .clinic-table thead tr {
            background: var(--surface-2);
        }

        .clinic-table th {
            text-align: left;
            padding: 10px 16px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .clinic-table td {
            padding: 12px 16px;
            color: var(--text-2);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .clinic-table tr:last-child td { border-bottom: none; }

        .clinic-table tbody tr {
            transition: background var(--dur) var(--ease);
        }
        .clinic-table tbody tr:hover { background: var(--surface-2); }

        .td-primary {
            font-weight: 600;
            color: var(--text) !important;
        }

        .td-link {
            font-weight: 600;
            color: var(--brand) !important;
            transition: color var(--dur) var(--ease);
        }
        .td-link:hover { color: var(--brand-2) !important; }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           BADGES
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .badge, .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.1px;
            white-space: nowrap;
        }
        .badge::before, .badge-pill::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .badge-emerald, .badge-green, .badge-active, .badge-paid, .badge-completed { background: var(--emerald-soft); color: var(--emerald); }
        .badge-emerald::before, .badge-green::before, .badge-active::before, .badge-paid::before, .badge-completed::before { background: var(--emerald); }

        .badge-amber, .badge-yellow, .badge-pending, .badge-unpaid { background: var(--amber-soft); color: var(--amber); }
        .badge-amber::before, .badge-yellow::before, .badge-pending::before, .badge-unpaid::before { background: var(--amber); }

        .badge-rose, .badge-red, .badge-cancelled, .badge-inactive { background: var(--rose-soft); color: var(--rose); }
        .badge-rose::before, .badge-red::before, .badge-cancelled::before, .badge-inactive::before { background: var(--rose); }

        .badge-sky, .badge-blue, .badge-confirmed { background: var(--sky-soft); color: var(--sky); }
        .badge-sky::before, .badge-blue::before, .badge-confirmed::before { background: var(--sky); }

        .badge-violet, .badge-purple { background: var(--violet-soft); color: var(--violet); }
        .badge-violet::before, .badge-purple::before { background: var(--violet); }

        .badge-brand { background: var(--brand-soft); color: var(--brand); }
        .badge-brand::before { background: var(--brand); }

        .badge-slate, .badge-gray { background: var(--slate-soft); color: var(--slate); }
        .badge-slate::before, .badge-gray::before { background: var(--slate); }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           FORMS
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 6px;
            letter-spacing: 0.1px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 9px 13px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            color: var(--text);
            font-size: 13.5px;
            line-height: 1.5;
            transition: border-color var(--dur) var(--ease), box-shadow var(--dur) var(--ease);
            outline: none;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 28px;
            transition: border-color var(--dur) var(--ease);
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 700px) { .form-grid-2 { grid-template-columns: 1fr; } }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           BUTTONS
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: var(--r-sm);
            font-size: 13.5px;
            font-weight: 600;
            border: none;
            font-family: inherit;
            cursor: pointer;
            transition: all var(--dur) var(--ease);
            text-decoration: none;
            white-space: nowrap;
        }
        .btn svg { width: 15px; height: 15px; flex-shrink: 0; }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 2px 6px var(--brand-glow);
        }
        .btn-primary:hover {
            background: var(--brand-2);
            box-shadow: var(--shadow-brand);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--rose);
            color: #fff;
        }
        .btn-danger:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-2);
        }
        .btn-outline:hover {
            background: var(--surface-2);
            border-color: var(--border-2);
            color: var(--text);
        }

        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* Careflow specific buttons (used in views) */
        .btn-careflow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-2) 100%);
            color: #fff;
            border: none;
            border-radius: var(--r-sm);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--dur) var(--ease);
            text-decoration: none;
            box-shadow: 0 2px 6px var(--brand-glow);
        }
        .btn-careflow:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-brand);
        }
        .btn-outline-careflow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-2);
            border-radius: var(--r-sm);
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--dur) var(--ease);
            text-decoration: none;
        }
        .btn-outline-careflow:hover {
            background: var(--surface-2);
            border-color: var(--brand);
            color: var(--brand);
            transform: translateY(-1px);
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           SEARCH WRAP & FORM (for patient/appointment views)
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .search-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .search-form {
            position: relative;
            flex: 1;
            max-width: 320px;
        }

        .search-form i,
        .search-form .bi-search {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }

        .search-form input {
            width: 100%;
            padding: 10px 12px 10px 36px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            color: var(--text);
            font-size: 13.5px;
            transition: all var(--dur) var(--ease);
            outline: none;
        }

        .search-form input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }

        .search-clear {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 18px;
            line-height: 1;
        }
        .search-clear:hover { color: var(--rose); }

        .sort-select {
            padding: 9px 12px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            color: var(--text);
            font-size: 13px;
            cursor: pointer;
            transition: all var(--dur) var(--ease);
            outline: none;
        }
        .sort-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           ALERTS / FLASH
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--r-sm);
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
            border-left: 3px solid;
            animation: slideIn .2s var(--ease);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-success {
            background: var(--emerald-soft);
            color: var(--emerald);
            border-color: var(--emerald);
        }
        .alert-danger {
            background: var(--rose-soft);
            color: var(--rose);
            border-color: var(--rose);
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           PAGINATION
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .pagination-wrap {
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .pagination-info {
            font-size: 12px;
            color: var(--text-muted);
        }
        .pagination-wrap nav { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
        .pagination-wrap span, .pagination-wrap a {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 500;
            border: 1px solid var(--border);
            color: var(--text-muted);
            text-decoration: none;
            transition: all var(--dur) var(--ease);
        }
        .pagination-wrap a:hover {
            background: var(--surface-2);
            color: var(--text);
            border-color: var(--border-2);
        }
        .pagination-wrap [aria-current="page"] span {
            background: var(--brand);
            color: #fff;
            border-color: var(--brand);
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           GENDER BARS
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .gender-bar-track {
            height: 8px;
            border-radius: 8px;
            background: var(--surface-3);
            overflow: hidden;
            margin: 8px 0 5px;
        }
        .gender-bar-fill {
            height: 100%;
            border-radius: 8px;
            transition: width .7s var(--ease);
        }
        .gender-legends {
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            color: var(--text-muted);
        }
        .gender-legend-dot {
            display: inline-block;
            width: 7px; height: 7px;
            border-radius: 50%;
            margin-right: 4px;
            vertical-align: middle;
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           EMPTY STATE
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }
        .empty-state svg { width: 38px; height: 38px; margin-bottom: 10px; opacity: .35; }
        .empty-state p { font-size: 13px; }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           UTILITIES
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .mb-5  { margin-bottom: 20px; }
        .mb-6  { margin-bottom: 24px; }
        .mt-4  { margin-top: 16px; }
        .text-muted { color: var(--text-muted); font-size: 12.5px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .d-inline { display: inline; }
        .me-1 { margin-right: 4px; }
        .me-2 { margin-right: 8px; }
        .ms-2 { margin-left: 8px; }
        .mt-2 { margin-top: 8px; }
        .py-4 { padding-top: 16px; padding-bottom: 16px; }
        .px-4 { padding-left: 16px; padding-right: 16px; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .w-full { width: 100%; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

        /* scrollbar */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
    </style>
</head>
<body>
<div class="app-shell">

    {{-- ━━━━━━━━ SIDEBAR ━━━━━━━━ --}}
    <aside class="sidebar">

        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-mark">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div>
                <div class="brand-name">Careflow</div>
                <div class="brand-sub">Clinic System</div>
            </div>
        </a>

        <div class="nav-section">
            <div class="nav-section-label">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                Dashboard
            </a>
            <a href="{{ route('patients.index') }}" class="nav-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                Patients
            </a>
            <a href="{{ route('appointments.index') }}" class="nav-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                Appointments
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Clinical</div>
            <a href="{{ route('medical-records.index') }}" class="nav-item {{ request()->routeIs('medical-records.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Medical Records
            </a>
            <a href="{{ route('billings.index') }}" class="nav-item {{ request()->routeIs('billings.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
                Billing
            </a>
        </div>

        @if(Auth::user()->role === 'admin')
        <div class="nav-section">
            <div class="nav-section-label">Administration</div>
            <a href="{{ route('doctors.index') }}" class="nav-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                </svg>
                Doctors
            </a>
        </div>
        @endif

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- ━━━━━━━━ TOPBAR ━━━━━━━━ --}}
    <header class="topbar">
        <div class="topbar-left">
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
            <div class="topbar-sep"></div>
            <span class="topbar-date">{{ now()->format('l, F j, Y') }}</span>
        </div>
        <div class="topbar-right">
            <span class="role-chip">{{ Auth::user()->role }}</span>
            <button class="icon-btn" @click="darkMode = !darkMode" :title="darkMode ? 'Switch to light' : 'Switch to dark'">
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                </svg>
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>
            </button>
        </div>
    </header>

    {{-- ━━━━━━━━ MAIN ━━━━━━━━ --}}
    <div class="main-wrap">
        <div class="main-content">

            @if(session('success'))
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </div>
    </div>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>