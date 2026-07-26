<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Transcent Profumo')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --font: 'Plus Jakarta Sans', 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --text: #111111;
            --text-muted: #6b7280;
            --surface: #ffffff;
            --surface-solid: #ffffff;
            --surface-muted: #f5f5f5;
            --border: rgba(0, 0, 0, 0.1);
            --border-strong: rgba(0, 0, 0, 0.18);
            --primary: #111111;
            --primary-hover: #333333;
            --gradient-btn: #111111;
            --shadow-card: 0 8px 24px rgba(0, 0, 0, 0.06);
            --radius-lg: 22px;
            --radius-xl: 26px;

            /* Minimalistic Accents (Replaced gold with slate gray) */
            --accent-gold: #334155;
            --accent-gold-light: rgba(71, 85, 105, 0.08);
            --shadow-premium: 0 16px 40px rgba(71, 85, 105, 0.08);

            /* Unified Luxury Status Colors (Restored to original colors) */
            --color-pending-bg: #fff7ed;
            --color-pending-text: #9a3412;
            --color-pending-border: #fed7aa;

            --color-progress-bg: #eff6ff;
            --color-progress-text: #1e40af;
            --color-progress-border: #bfdbfe;

            --color-completed-bg: #f0fdf4;
            --color-completed-text: #166534;
            --color-completed-border: #bbf7d0;

            --color-cancelled-bg: #fef2f2;
            --color-cancelled-text: #991b1b;
            --color-cancelled-border: #fecaca;

            /* Dark mode specific */
            --dm-bg-primary: #0f1117;
            --dm-bg-secondary: #1a1d27;
            --dm-bg-tertiary: #242733;
            --dm-bg-elevated: #2a2d3a;
            --dm-text-primary: #e8eaed;
            --dm-text-secondary: #9aa0b0;
            --dm-border: rgba(255, 255, 255, 0.08);
            --dm-border-strong: rgba(255, 255, 255, 0.14);

            font-family: var(--font);
            color: var(--text);
        }

        /* ===== DARK MODE ===== */
        html[data-theme="dark"] {
            color-scheme: dark;
            --text: #e8eaed;
            --text-muted: #9aa0b0;
            --surface: #1a1d27;
            --surface-solid: #1a1d27;
            --surface-muted: #242733;
            --border: rgba(255, 255, 255, 0.08);
            --border-strong: rgba(255, 255, 255, 0.14);
            --primary: #e8eaed;
            --primary-hover: #ffffff;
            --gradient-btn: #e8eaed;
            --shadow-card: 0 8px 24px rgba(0, 0, 0, 0.3);
            --accent-gold: #94a3b8;
            --accent-gold-light: rgba(148, 163, 184, 0.1);
            --shadow-premium: 0 16px 40px rgba(0, 0, 0, 0.3);

            --color-pending-bg: rgba(154, 52, 18, 0.15);
            --color-pending-text: #fb923c;
            --color-pending-border: rgba(251, 146, 60, 0.25);

            --color-progress-bg: rgba(30, 64, 175, 0.15);
            --color-progress-text: #60a5fa;
            --color-progress-border: rgba(96, 165, 250, 0.25);

            --color-completed-bg: rgba(22, 101, 52, 0.15);
            --color-completed-text: #4ade80;
            --color-completed-border: rgba(74, 222, 128, 0.25);

            --color-cancelled-bg: rgba(153, 27, 27, 0.15);
            --color-cancelled-text: #f87171;
            --color-cancelled-border: rgba(248, 113, 113, 0.25);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text);
            background: linear-gradient(-45deg, #f5f5f5, #eef2f3, #e2e8f0, #f8fafc);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            transition: background .3s ease, color .3s ease;
        }
        html[data-theme="dark"] body {
            background: linear-gradient(-45deg, #0f1117, #131620, #171b26, #0f1117);
            background-size: 400% 400%;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .dashboard-wrapper {
            width: 100%;
            min-height: calc(100vh - 48px);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        button, select { appearance: none; -webkit-appearance: none; -moz-appearance: none; }
        button { border: none; background: none; }
        input, textarea { outline: none; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 52px;
            padding: 0 18px;
            border: none;
            border-radius: 14px;
            background: var(--gradient-btn);
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            line-height: 1.2;
            text-align: center;
        }
        .btn:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        .btn:active { transform: translateY(1px); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .btn:focus-visible { outline: 2px solid rgba(0, 0, 0, 0.25); outline-offset: 3px; }
        .btn-small { padding: 0 14px; min-height: 44px; font-size: 0.92rem; }
        .btn-primary {
            background: var(--gradient-btn);
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary {
            background: #f4f4f5;
            color: #111111;
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: #e5e5e5; border-color: var(--border-strong); }
        .btn-danger { background: #111111; color: #fff; }
        .btn-danger:hover { background: #333333; }
        .btn-ghost {
            background: #f4f4f5;
            color: #111111;
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { background: #e5e5e5; }
        .page-shell { width: min(1720px, 98vw); max-width: 1720px; margin: 0 auto; padding: 16px 20px 28px; }
        .brand-panel {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 22px 28px;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border);
            position: sticky;
            top: 16px;
            z-index: 1000;
        }
        .brand-chip {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: #111111;
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 28px;
            font-weight: 800;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
            padding: 0;
            overflow: hidden;
        }
        .brand-title h1 {
            margin: 0;
            font-size: clamp(1.9rem, 2.1vw, 2.9rem);
            letter-spacing: -0.02em;
            font-weight: 800;
            color: #111111;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .brand-title p {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .top-nav { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; margin-top: 0; }
        .nav-tabs {
            display: inline-flex;
            gap: 6px;
            background: #f4f4f5;
            border-radius: 16px;
            padding: 6px;
            border: 1px solid var(--border);
        }
        .nav-link {
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 700;
            color: #6b7280;
            transition: background .15s ease, color .15s ease;
        }
        .nav-link.active {
            background: var(--gradient-btn);
            color: #fff;
        }
        .nav-link:not(.active):hover { background: #e5e5e5; color: #111111; }
        .text-slate { color: #64748b; }
        .text-lg { font-size: 1.125rem; }
        .brand-row { display: flex; gap: 18px; align-items: center; }
        .section-lead { margin: 6px 0 0; }
        .order-items-list { display: grid; gap: 10px; }
        .order-item-line { font-size: 0.95rem; color: #334155; line-height: 1.5; }
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: #f4f4f5;
            color: #111111;
            font-size: 0.88rem;
            font-weight: 700;
            border: 1px solid var(--border);
        }
        .filter-chip a { color: #374151; text-decoration: underline; text-underline-offset: 2px; }
        .action-group { display: inline-flex; gap: 12px; flex-wrap: wrap; }
        .user-dropdown-container { position: relative; display: inline-block; }
        .user-dropdown-container.open { z-index: 9999; }
        .user-dropdown-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #ffffff;
            color: #111111;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        .user-dropdown-toggle:hover {
            background: #f4f4f5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .user-dropdown-toggle:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 240px;
            background: #ffffff;
            border: 1px solid #dbe3ef;
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18);
            padding: 8px;
            z-index: 10000;
            display: none;
        }
        .user-dropdown-container.open .user-dropdown-menu { display: block; }
        .user-menu-header { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; margin-bottom: 8px; }
        .user-menu-header .user-name { font-weight: 800; color: #111111; font-size: 1.05rem; letter-spacing: -0.01em; }
        .user-menu-header .user-role { color: #64748b; font-size: 0.85rem; font-weight: 600; margin-top: 2px; }
        .user-menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-align: left;
            padding: 12px 16px;
            background: transparent;
            border: none;
            border-radius: 10px;
            color: #334155;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s ease;
            font-family: inherit;
        }
        .user-menu-item:hover { background: #f4f4f5; color: #111111; }
        .user-menu-item.text-danger { color: #dc2626; }
        .user-menu-item.text-danger:hover { background: #fef2f2; color: #b91c1c; }
        .page-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            padding: 28px 32px 32px;
            flex: 1;
            border: 1px solid var(--border);
            transition: box-shadow .3s ease;
            width: 100%;
            min-width: 0;
        }
        .page-card:hover {
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
        }
        .dashboard-card { border-color: var(--border); }
        .panel-row { display: flex; flex-wrap: wrap; gap: 18px; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .toolbar-row { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .toolbar-filters { display: flex; flex: 1 1 420px; min-width: 260px; gap: 12px; }
        .toolbar-filters .search-form { width: 100%; }
        .search-form { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; width: 100%; }
        .search-input, .search-select {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #fff;
            color: #0f172a;
            padding: 14px 16px;
            transition: border-color .15s ease, box-shadow .15s ease;
            min-height: 52px;
        }
        .search-input { flex: 1 1 260px; min-width: 240px; }
        .search-select { flex: 0 0 220px; position: relative; background-image: linear-gradient(45deg, transparent 50%, #64748b 50%), linear-gradient(135deg, #64748b 50%, transparent 50%);
            background-position: calc(100% - 18px) calc(50% - 4px), calc(100% - 13px) calc(50% - 4px);
            background-size: 6px 6px;
            background-repeat: no-repeat;
            padding-right: 36px;
            appearance: none;
        }
        input[type="date"].search-select {
            background-image: none;
            padding-right: 16px;
        }
        .search-input::placeholder { color: #64748b; }
        .search-input:focus, .search-select:focus { outline: none; border-color: #111111; box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08); background: #fff; }
        .search-form button { min-height: 52px; }
        .form-group select {
            background-image: linear-gradient(45deg, transparent 50%, #64748b 50%), linear-gradient(135deg, #64748b 50%, transparent 50%);
            background-position: calc(100% - 18px) calc(50% - 4px), calc(100% - 13px) calc(50% - 4px);
            background-size: 6px 6px;
            background-repeat: no-repeat;
            padding-right: 36px;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #111111;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
            transition: all .2s ease;
        }
        .status-cell { position: relative; overflow: visible; }
        .orders-table, .orders-table tbody, .orders-table tr, .orders-table td { overflow: visible; }
        .orders-table td { position: static; }
        .status-dropdown { position: relative; display: inline-flex; width: 200px; min-width: 200px; max-width: 200px; }
        .status-dropdown.open { z-index: 9999; }
        .status-dropdown .status-pill { display: inline-flex; align-items: center; justify-content: center; width: 100%; min-height: 48px; padding: 14px 18px; border-radius: 14px; background: #fafafa; color: #111111; box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.12); cursor: pointer; transition: background .2s ease, box-shadow .2s ease; white-space: nowrap; font-weight: 700; }
        .status-dropdown .status-pill:hover { background: #f0f0f0; box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.2); }
        .status-dropdown .status-pill .dropdown-arrow { margin-left: 10px; font-size: 1rem; }
        .status-dropdown .status-menu { position: absolute; top: calc(100% + 8px); left: 0; min-width: 220px; width: max-content; max-width: 100vw; background: #ffffff; border: 1px solid #dbe3ef; border-radius: 16px; box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18); padding: 8px; z-index: 10000; display: none; }
        .status-dropdown.open .status-menu { display: block; }
        .status-option { width: 100%; border: none; background: transparent; padding: 12px 14px; border-radius: 12px; text-align: left; cursor: pointer; font-weight: 600; color: #0f172a; transition: background .16s ease, color .16s ease; }
        .status-option:hover, .status-option:focus { background: #f4f4f5; color: #111111; }
        .status-pill-text { display: inline-block; white-space: nowrap; }
        .status-option { white-space: nowrap; }
        .status-dropdown .status-pill.status-pill-not-yet-in-progress, .status-dropdown .status-pill.status-pill-not-yet { color: var(--color-pending-text); background: var(--color-pending-bg); border: 1px solid var(--color-pending-border); box-shadow: none; }
        .status-dropdown .status-pill.status-pill-in-progress { color: var(--color-progress-text); background: var(--color-progress-bg); border: 1px solid var(--color-progress-border); box-shadow: none; }
        .status-dropdown .status-pill.status-pill-completed { color: var(--color-completed-text); background: var(--color-completed-bg); border: 1px solid var(--color-completed-border); box-shadow: none; }
        .status-dropdown .status-pill.status-pill-cancelled { color: var(--color-cancelled-text); background: var(--color-cancelled-bg); border: 1px solid var(--color-cancelled-border); box-shadow: none; }
        /* Status pill icons are handled by unified classes below */
        .status-dropdown .status-menu { max-height: 220px; overflow-y: auto; }
        .status-dropdown .status-menu.is-fixed { position: fixed; top: auto; left: auto; z-index: 10001; }
        .section-header { width: 100%; margin-bottom: 16px; align-items: flex-end !important; }
        .section-header h2 { margin: 0; font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; }
        .section-header p { margin: 8px 0 0; color: #64748b; }
        .section-header .orders-count { flex-shrink: 0; }
        .table-wrapper { width: 100%; overflow-x: auto; margin-top: 18px; }
        .spreadsheet-wrapper {
            overflow: auto;
            margin-top: 12px;
            background: var(--surface-solid);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }
        .spreadsheet-table {
            width: 100%;
            min-width: 1000px;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 0.9rem;
        }
        .spreadsheet-table th, .spreadsheet-table td {
            padding: 14px 12px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #e5e7eb;
        }
        .spreadsheet-table th {
            color: #111111;
            font-weight: 700;
            background: #fafafa;
            white-space: nowrap;
            line-height: 1.3;
            font-size: 0.82rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .spreadsheet-table td {
            background: #fff;
            color: #1f2937;
            overflow-wrap: break-word;
            word-break: break-word;
        }
        .spreadsheet-table tbody tr:hover td { background: #fafafa; }
        .spreadsheet-table tbody tr:last-child td { border-bottom: none; }
        .spreadsheet-table td.col-total, .spreadsheet-table th.col-total { text-align: right; white-space: nowrap; }
        .spreadsheet-table .item-list { display: grid; gap: 8px; }
        .spreadsheet-table .item-list div { line-height: 1.45; color: #334155; font-size: 0.88rem; }
        .spreadsheet-table .status-badge {
            display: inline-flex;
            padding: 7px 12px;
            font-size: 0.8rem;
            white-space: nowrap;
            text-align: center;
            line-height: 1.3;
        }
        .spreadsheet-table .col-order-id { font-size: 0.84rem; line-height: 1.4; word-break: break-all; }
        .spreadsheet-table .col-phone { white-space: nowrap; }
        .spreadsheet-table .spreadsheet-date { white-space: nowrap; }
        .spreadsheet-table .spreadsheet-time { display: block; font-size: 0.8rem; margin-top: 2px; }
        .spreadsheet-table th:nth-child(1), .spreadsheet-table td:nth-child(1) { width: 11%; }
        .spreadsheet-table th:nth-child(2), .spreadsheet-table td:nth-child(2) { width: 7%; }
        .spreadsheet-table th:nth-child(3), .spreadsheet-table td:nth-child(3) { width: 11%; }
        .spreadsheet-table th:nth-child(4), .spreadsheet-table td:nth-child(4) { width: 8%; }
        .spreadsheet-table th:nth-child(5), .spreadsheet-table td:nth-child(5) { width: 8%; }
        .spreadsheet-table th:nth-child(6), .spreadsheet-table td:nth-child(6) { width: 13%; }
        .spreadsheet-table th:nth-child(7), .spreadsheet-table td:nth-child(7) { width: 7%; }
        .spreadsheet-table th:nth-child(8), .spreadsheet-table td:nth-child(8) { width: 7%; }
        .spreadsheet-table th:nth-child(9), .spreadsheet-table td:nth-child(9) { width: 8%; }
        .spreadsheet-table th:nth-child(10), .spreadsheet-table td:nth-child(10) { width: 16%; }
        .spreadsheet-table th:nth-child(11), .spreadsheet-table td:nth-child(11) { width: 7%; }
        .orders-table {
            width: 100%;
            min-width: 820px;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--surface-solid);
            border-radius: var(--radius-xl);
            overflow: visible;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
        }
        .orders-table th, .orders-table td { padding: 18px 20px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; text-align: center; }
        .orders-table th {
            background: #fafafa;
            color: #111111;
            font-weight: 700;
            border-bottom: 1px solid #e5e5e5;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            font-size: 0.78rem;
        }
        .orders-table th:first-child { border-top-left-radius: 28px; }
        .orders-table th:last-child { border-top-right-radius: 28px; }
        .orders-table td { background: #ffffff; color: #1f2937; }
        .orders-table td.col-total, .orders-table th.col-total { text-align: center; white-space: nowrap; }
        .orders-table tbody tr:hover td { background: #fafafa; }
        .orders-table tbody tr:last-child td { border-bottom: none; }
        .orders-table tbody tr:last-child td:first-child { border-bottom-left-radius: 28px; }
        .orders-table tbody tr:last-child td:last-child { border-bottom-right-radius: 28px; }
        .orders-table td .row-actions { display: grid; grid-template-columns: repeat(2, 90px); gap: 10px; align-items: center; justify-content: center; }
        .status-badge { display: inline-flex; align-items: center; padding: 8px 14px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; white-space: nowrap; }
        .role-badge { display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; white-space: nowrap; text-align: center; }
        .status-badge-not-yet-in-progress, .status-badge-not-yet { color: var(--color-pending-text); background: var(--color-pending-bg); border: 1px solid var(--color-pending-border); }
        .status-badge-in-progress { color: var(--color-progress-text); background: var(--color-progress-bg); border: 1px solid var(--color-progress-border); }
        .status-badge-completed { color: var(--color-completed-text); background: var(--color-completed-bg); border: 1px solid var(--color-completed-border); }
        .status-badge-cancelled { color: var(--color-cancelled-text); background: var(--color-cancelled-bg); border: 1px solid var(--color-cancelled-border); }
        /* Base styles for status indicator icons */
        .status-badge::before,
        .receipt-badge::before,
        .status-dropdown .status-pill::before {
            content: "";
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 8px;
            flex-shrink: 0;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        /* Completed (Green) */
        .status-badge-completed::before,
        .status-dropdown .status-pill.status-pill-completed::before,
        .receipt-badge.badge-green::before,
        .badge-green::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23166534' stroke-width='3.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpolyline points='9 11 12 14 17 9'%3E%3C/polyline%3E%3C/svg%3E");
        }

        /* In Progress (Blue) */
        .status-badge-in-progress::before,
        .status-dropdown .status-pill.status-pill-in-progress::before,
        .receipt-badge.badge-orange::before,
        .badge-orange::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231e40af' stroke-width='3.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpolyline points='12 6 12 12 16 14'%3E%3C/polyline%3E%3C/svg%3E");
        }

        /* Cancelled (Red) */
        .status-badge-cancelled::before,
        .status-dropdown .status-pill.status-pill-cancelled::before,
        .receipt-badge.badge-gray::before,
        .badge-gray::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23991b1b' stroke-width='3.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cline x1='15' y1='9' x2='9' y2='15'%3E%3C/line%3E%3Cline x1='9' y1='9' x2='15' y2='15'%3E%3C/line%3E%3C/svg%3E");
        }

        /* Pending / Not Yet (Orange/Brown) */
        .status-badge-not-yet::before,
        .status-badge-not-yet-in-progress::before,
        .status-dropdown .status-pill.status-pill-not-yet::before,
        .status-dropdown .status-pill.status-pill-not-yet-in-progress::before,
        .receipt-badge.badge-red::before,
        .badge-red::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239a3412' stroke-width='3.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cline x1='12' y1='8' x2='12' y2='12'%3E%3C/line%3E%3Cline x1='12' y1='16' x2='12.01' y2='16'%3E%3C/line%3E%3C/svg%3E");
        }
        /* Items Sold (Box Icon) */
        .status-badge-sold::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z'%3E%3C/path%3E%3Cpolyline points='3.27 6.96 12 12.01 20.73 6.96'%3E%3C/polyline%3E%3Cline x1='12' y1='22.08' x2='12' y2='12'%3E%3C/line%3E%3C/svg%3E");
        }
        .row-actions form { display: inline-flex; margin: 0; }
        .row-actions .btn {
            font-size: 0.92rem;
            min-height: 44px;
            height: 44px;
            width: 90px;
            min-width: 90px;
            padding: 0 8px;
            justify-content: center;
        }
        .row-actions .btn-secondary { background: #f4f4f5; color: #111111; }
        .row-actions .btn-secondary:hover { background: #e5e5e5; }
        .row-actions .btn-danger { background: #111111; color: #fff; }
        .row-actions .btn-danger:hover { background: #333333; }
        .row-actions .btn-primary { background: #f4f4f5; color: #111111; }
        .row-actions .btn-primary:hover { background: #e5e5e5; }
        .panel-row .control { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        .status-chip { display: none; }
        .card-panel {
            background: #fafafa;
            border-radius: var(--radius-lg);
            padding: 24px 28px 28px;
            border: 1px solid var(--border);
        }
        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: #475569;
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px dashed rgba(0, 0, 0, 0.2);
        }
        .empty-state-icon { font-size: 2.5rem; margin-bottom: 12px; line-height: 1; }
        .empty-state p { margin: 0; font-size: 1.05rem; font-weight: 600; color: #334155; }
        .empty-state .text-slate { margin-top: 8px; font-weight: 400; }
        .empty-state .btn { margin-top: 18px; }
        .empty-state strong { display: block; margin-top: 12px; color: #111827; }
        .modal-title { min-width: 0; display: grid; gap: 10px; }
        .modal-return-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: auto;
            padding: 8px 14px;
            border-radius: 12px;
            border: none;
            background: #f4f4f5;
            color: #111111;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: background .15s ease;
        }
        .modal-return-btn:hover { background: #e5e5e5; }
        .receipt-header-panel { justify-content: flex-start; margin-bottom: 22px; }
        .modal-backdrop { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 11000; }
        .modal-container { position: fixed; inset: 0; z-index: 11100; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; overflow-y: auto; }
        .modal-card {
            width: min(980px, 92vw);
            max-height: 90vh;
            overflow-y: auto;
            background: #fff;
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--border);
        }
        .modal-header { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; justify-content: flex-start; margin-bottom: 20px; }
        .modal-heading { display: block; gap: 10px; margin-bottom: 20px; }
        .modal-label {
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            border-radius: 10px;
            background: #f4f4f5;
            color: #111111;
            font-weight: 700;
            font-size: 0.9rem;
            border: 1px solid var(--border);
        }
        .modal-heading h2 { margin: 0; font-size: 1.9rem; line-height: 1.2; font-weight: 800; letter-spacing: -0.03em; }
        .step-tabs { display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 0; }
        .step-tab {
            border: 1px solid var(--border);
            background: #f4f4f5;
            color: #111111;
            padding: 10px 18px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: background .15s ease, color .15s ease;
        }
        .step-tab:hover:not(.active) { background: #e5e5e5; }
        .step-tab.active { background: var(--gradient-btn); color: #fff; border-color: transparent; }
        .items-section-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 8px; }
        .items-section-header h3 { margin: 0; font-size: 1.05rem; }
        .order-total-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            padding: 16px 20px;
            border-radius: 18px;
            background: #f4f4f5;
            color: #111111;
            font-weight: 700;
            border: 1px solid var(--border);
        }
        .order-total-bar span:last-child { font-size: 1.15rem; color: #111111; }
        .modal-card.has-sticky-footer { display: flex; flex-direction: column; padding-bottom: 0; overflow: hidden; }
        .modal-card.has-sticky-footer #order-form { display: flex; flex-direction: column; flex: 1; min-height: 0; }
        .modal-card.has-sticky-footer .modal-form-body { flex: 1; overflow-y: auto; padding-bottom: 8px; }
        .modal-card.has-sticky-footer .modal-actions-sticky { position: sticky; bottom: 0; background: #fff; border-top: 1px solid #e2e8f0; margin-top: 0; padding: 18px 0 32px; z-index: 2; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; }
        .form-group { display: grid; gap: 10px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label { font-size: 0.92rem; color: #4b5563; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            background-color: #f8fafc;
            color: #111827;
            font-size: 15px;
            padding: 0 16px;
            outline: none;
            box-sizing: border-box;
        }
        .form-group input, .form-group select { height: 52px; }
        .form-group textarea {
            min-height: 110px;
            padding-top: 14px;
            resize: vertical;
        }
        .modal-grid { display: grid; gap: 18px; }
        .modal-actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; justify-content: flex-end; margin-top: 22px; }
        .modal-close { display: none !important; }
        .receipt-header-panel { justify-content: flex-start; margin-bottom: 22px; }
        .form-step { display: none; }
        .form-step.active { display: block; }
        .step-row { display: none !important; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        .item-table th, .item-table td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        .item-table th { color: #475569; font-weight: 700; background: transparent; text-align: left; }
        .item-table th:nth-child(1) { width: 34%; }
        .item-table th:nth-child(2) { width: 22%; }
        .item-table th:nth-child(3) { width: 16%; }
        .item-table th:nth-child(4) { width: 14%; }
        .item-table th:nth-child(5) { width: 14%; }
        .item-table td { background: transparent; overflow: visible; position: relative; }
        .item-table input[type="text"],
        .item-table input.price-input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            background: #f8fafc;
            color: #111827;
            font-size: 15px;
            padding: 0 14px;
            height: 48px;
            outline: none;
            box-sizing: border-box;
        }
        .item-table input.price-input::placeholder { color: #94a3b8; }
        .item-table input:focus { border-color: #111111; box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08); }
        .item-table .qty-control { display: inline-flex; align-items: center; gap: 8px; }
        .item-table .qty-input {
            width: 72px;
            min-width: 72px;
            height: 48px;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            background: #f8fafc;
            color: #111827;
            font-size: 15px;
            text-align: center;
            padding: 0 8px;
            outline: none;
            box-sizing: border-box;
        }
        .item-table .qty-btn {
            width: 40px;
            height: 40px;
            min-height: 40px;
            padding: 0;
            border-radius: 10px;
            background: #f4f4f5;
            color: #111111;
            font-weight: 700;
            font-size: 1.1rem;
            line-height: 1;
            flex-shrink: 0;
        }
        .item-table .qty-btn:hover { background: #e5e5e5; }
        .item-table .row-total-wrap { font-weight: 600; color: #334155; white-space: nowrap; }
        .row-actions { display: flex; gap: 8px; align-items: center; }
        .row-actions button { border-radius: 14px; }
        .receipt-panel { background: #fff; border-radius: 28px; padding: 28px; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.12); }
        .receipt-top { display: flex; flex-wrap: wrap; gap: 18px; align-items: center; justify-content: space-between; margin-bottom: 26px; }
        .receipt-badge { display: inline-flex; align-items: center; gap: 10px; padding: 8px 14px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; white-space: nowrap; }
        .receipt-badge.badge-red, .badge-red { color: var(--color-pending-text); background: var(--color-pending-bg); border: 1px solid var(--color-pending-border); }
        .receipt-badge.badge-orange, .badge-orange { color: var(--color-progress-text); background: var(--color-progress-bg); border: 1px solid var(--color-progress-border); }
        .receipt-badge.badge-green, .badge-green { color: var(--color-completed-text); background: var(--color-completed-bg); border: 1px solid var(--color-completed-border); }
        .receipt-badge.badge-gray, .badge-gray { color: var(--color-cancelled-text); background: var(--color-cancelled-bg); border: 1px solid var(--color-cancelled-border); }
        .receipt-brand-row { display: flex; gap: 14px; align-items: center; }
        .receipt-eyebrow { margin: 0; font-size: 0.85rem; color: #4b5563; text-transform: uppercase; letter-spacing: 0.12em; }
        .receipt-title { margin: 6px 0 0; }
        .receipt-box-label { margin: 0 0 8px; }
        .receipt-box-label.spaced { margin: 8px 0 0; }
        .table-scroll { overflow: visible; -webkit-overflow-scrolling: touch; }
        body.modal-open { overflow: hidden; }
        .receipt-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .receipt-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .receipt-section { display: grid; gap: 18px; margin-bottom: 20px; }
        .receipt-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .receipt-box { padding: 20px; border-radius: 22px; background: #f8fafc; }
        .receipt-box strong { display: block; margin-top: 8px; color: #111827; }
        .receipt-items { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .receipt-items th, .receipt-items td { padding: 14px 12px; border-bottom: 1px solid #e2e8f0; }
        .receipt-items th { color: #475569; font-weight: 700; text-align: left; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .receipt-items td { font-size: 0.95rem; color: #0f172a; }
        .receipt-total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-radius: 18px;
            background: #0f172a;
            color: #fff;
            margin-top: 18px;
        }
        .receipt-footer { margin-top: 24px; color: #475569; font-size: 0.95rem; display: grid; gap: 8px; }

        /* ===== Receipt Modal Styles ===== */
        .receipt-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .brand-block {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        .brand-name {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            font-weight: 700;
        }
        .label-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2px;
        }
        .order-meta-block {
            display: flex;
            gap: 20px;
            background: #f1f5f9;
            padding: 10px 16px;
            border-radius: 14px;
        }
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        .meta-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.05em;
        }
        .meta-value {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
        }
        .receipt-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .receipt-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 18px;
            background: #ffffff;
        }
        .ship-card {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .card-header-tag {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 14px;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .ship-body { gap: 14px; }
        .field-block {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .field-label {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.06em;
        }
        .field-value { color: #0f172a; }
        .name-value, .phone-value, .address-value {
            font-size: 1.12rem;
            font-weight: 800;
            line-height: 1.35;
            color: #0f172a;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .detail-cell {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }
        .col-qty { text-align: center; }
        .col-price, .col-total { text-align: right; }
        .receipt-table-wrapper { width: 100%; margin-top: 20px; }
        .total-title {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .total-price {
            font-size: 1.35rem;
            font-weight: 800;
        }
        @keyframes slideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }
        .flash-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 12000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        .flash-alert { position: relative; padding: 16px 48px 16px 20px; border-radius: 18px; font-weight: 600; box-shadow: 0 12px 32px rgba(0,0,0,0.12); pointer-events: auto; animation: slideInRight 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; max-width: 400px; }
        .flash-alert.closing { animation: fadeOutRight 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        .error-alert, .flash-alert.error-alert { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .success-alert, .flash-alert.success-alert { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .flash-dismiss { position: absolute; top: 50%; right: 14px; transform: translateY(-50%); width: 32px; height: 32px; border-radius: 999px; background: transparent; color: inherit; font-size: 1.25rem; line-height: 1; cursor: pointer; opacity: 0.6; border: none; transition: all .2s ease; }
        .flash-dismiss:hover { opacity: 1; background: rgba(0,0,0,.08); }
        .hidden { display: none !important; }

        /* Confirm modal overlay */
        .overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 11000; display: grid; place-items: center; }
        .modal-confirm {
            background: #fff;
            padding: 28px;
            border-radius: 24px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--border);
            z-index: 11100;
            width: min(480px, 96%);
        }
        .modal-confirm h3 { margin: 0; font-size: 1.15rem; }
        .modal-confirm .modal-actions { justify-content: flex-end; margin-top: 20px; }
        .orders-count {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 999px;
            background: #fff;
            color: #111111;
            font-size: 0.88rem;
            font-weight: 700;
            border: 1px solid var(--border);
        }
        @media (max-width: 900px) {
            .brand-panel, .top-nav, .page-card, .modal-card { padding: 20px; }
            .page-card { border-radius: 24px; }
            .receipt-row { grid-template-columns: 1fr; }
            .page-shell { width: 100%; max-width: 100%; padding: 12px 12px 24px; }
            .dashboard-wrapper { min-height: auto; }
            .brand-panel { flex-direction: column; align-items: stretch; }
            .top-nav { flex-direction: column; align-items: stretch; }
            .nav-tabs { width: 100%; justify-content: center; }
            .nav-link { flex: 1 1 auto; text-align: center; }
            .action-group { justify-content: center; width: 100%; }
            .toolbar-row, .panel-row { flex-direction: column; align-items: stretch; }
            .toolbar-filters { flex-direction: column; width: 100%; }
            .search-input, .search-select, .search-form button { width: 100%; }
            .search-form { gap: 12px; }
            .orders-table { min-width: 100%; }
            .spreadsheet-table th, .spreadsheet-table td { padding: 8px 6px; font-size: 0.8rem; }
            .orders-table td .row-actions { grid-template-columns: repeat(2, minmax(72px, 1fr)); }
            .row-actions .btn { width: 100%; min-width: 0; }
            .filter-chip { width: 100%; justify-content: center; }
            .section-header { flex-direction: column; align-items: flex-start !important; }
            .flash-container { top: 12px; right: 12px; left: 12px; }
            .flash-alert { width: 100%; max-width: 100%; box-sizing: border-box; }
        }

        /* ===== DARK MODE OVERRIDES ===== */
        html[data-theme="dark"] .brand-panel {
            background: rgba(26, 29, 39, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        html[data-theme="dark"] .brand-title h1 { color: #e8eaed; }
        html[data-theme="dark"] .brand-chip { background: #242733; border-color: var(--border); }
        html[data-theme="dark"] .nav-tabs { background: #242733; }
        html[data-theme="dark"] .nav-link { color: #9aa0b0; }
        html[data-theme="dark"] .nav-link:not(.active):hover { background: #2a2d3a; color: #e8eaed; }
        html[data-theme="dark"] .nav-link.active { background: #e8eaed; color: #111111; }

        /* Buttons */
        html[data-theme="dark"] .btn-primary { background: #e8eaed; color: #111111; }
        html[data-theme="dark"] .btn-primary:hover { background: #ffffff; color: #000; }
        html[data-theme="dark"] .btn-secondary { background: #242733; color: #e8eaed; border-color: var(--border); }
        html[data-theme="dark"] .btn-secondary:hover { background: #2a2d3a; border-color: var(--border-strong); }
        html[data-theme="dark"] .btn-ghost { background: #242733; color: #e8eaed; border-color: var(--border); }
        html[data-theme="dark"] .btn-ghost:hover { background: #2a2d3a; }
        html[data-theme="dark"] .btn-danger { background: #e8eaed; color: #111111; }
        html[data-theme="dark"] .btn-danger:hover { background: #ffffff; }

        /* Search & Form */
        html[data-theme="dark"] .search-input,
        html[data-theme="dark"] .search-select {
            background: #242733;
            color: #e8eaed;
            border-color: var(--border);
        }
        html[data-theme="dark"] .search-input::placeholder { color: #6b7280; }
        html[data-theme="dark"] .search-input:focus,
        html[data-theme="dark"] .search-select:focus {
            border-color: #e8eaed;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.06);
            background: #1a1d27;
        }
        html[data-theme="dark"] .form-group input,
        html[data-theme="dark"] .form-group select,
        html[data-theme="dark"] .form-group textarea {
            background-color: #242733;
            border-color: rgba(255,255,255,0.1);
            color: #e8eaed;
        }
        html[data-theme="dark"] .form-group input:focus,
        html[data-theme="dark"] .form-group select:focus,
        html[data-theme="dark"] .form-group textarea:focus {
            border-color: #e8eaed;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.06);
        }
        html[data-theme="dark"] .form-group label { color: #9aa0b0; }

        /* Tables */
        html[data-theme="dark"] .orders-table { background: #1a1d27; }
        html[data-theme="dark"] .orders-table th { background: #242733; color: #9aa0b0; border-bottom-color: rgba(255,255,255,0.06); }
        html[data-theme="dark"] .orders-table td { background: #1a1d27; color: #e8eaed; border-bottom-color: rgba(255,255,255,0.04); }
        html[data-theme="dark"] .orders-table th, html[data-theme="dark"] .orders-table td { border-bottom-color: rgba(255,255,255,0.06); }
        html[data-theme="dark"] .orders-table tbody tr:hover td { background: #242733; }
        html[data-theme="dark"] .order-item-line { color: #cbd5e1; }
        html[data-theme="dark"] .order-item-line strong { color: #e8eaed; font-weight: 700; }
        html[data-theme="dark"] .spreadsheet-wrapper { background: #1a1d27; }
        html[data-theme="dark"] .spreadsheet-table th { background: #242733; color: #9aa0b0; }
        html[data-theme="dark"] .spreadsheet-table td { background: #1a1d27; color: #e8eaed; }
        html[data-theme="dark"] .spreadsheet-table .item-list div { color: #cbd5e1; }
        html[data-theme="dark"] .spreadsheet-table th,
        html[data-theme="dark"] .spreadsheet-table td { border-bottom-color: rgba(255,255,255,0.06); }
        html[data-theme="dark"] .spreadsheet-table tbody tr:hover td { background: #242733; }

        /* Item table (order modal) */
        html[data-theme="dark"] .item-table th { color: #9aa0b0; }
        html[data-theme="dark"] .item-table th, html[data-theme="dark"] .item-table td { border-bottom-color: rgba(255,255,255,0.06); }
        html[data-theme="dark"] .item-table input[type="text"],
        html[data-theme="dark"] .item-table input.price-input,
        html[data-theme="dark"] .item-table .qty-input {
            background: #242733;
            border-color: rgba(255,255,255,0.1);
            color: #e8eaed;
        }
        html[data-theme="dark"] .item-table input:focus { border-color: #e8eaed; box-shadow: 0 0 0 4px rgba(255,255,255,0.06); }
        html[data-theme="dark"] .item-table .qty-btn { background: #2a2d3a; color: #e8eaed; }
        html[data-theme="dark"] .item-table .qty-btn:hover { background: #363a4a; }
        html[data-theme="dark"] .item-table .row-total-wrap { color: #c8ccd4; }

        /* Status */
        html[data-theme="dark"] .status-dropdown .status-pill { background: #242733; color: #e8eaed; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08); }
        html[data-theme="dark"] .status-dropdown .status-pill:hover { background: #2a2d3a; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.14); }
        html[data-theme="dark"] .status-dropdown .status-menu { background: #242733; border-color: rgba(255,255,255,0.08); box-shadow: 0 16px 40px rgba(0,0,0,0.4); }
        html[data-theme="dark"] .status-option { color: #e8eaed; }
        html[data-theme="dark"] .status-option:hover,
        html[data-theme="dark"] .status-option:focus { background: #2a2d3a; color: #ffffff; }

        /* Page card & panels */
        html[data-theme="dark"] .page-card:hover { box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
        html[data-theme="dark"] .card-panel { background: #242733; }
        html[data-theme="dark"] .filter-chip { background: #242733; color: #e8eaed; }
        html[data-theme="dark"] .section-header h2 { color: #e8eaed; }
        html[data-theme="dark"] .section-header p { color: #9aa0b0; }

        /* Empty state */
        html[data-theme="dark"] .empty-state { background: #1a1d27; border-color: rgba(255,255,255,0.12); color: #9aa0b0; }
        html[data-theme="dark"] .empty-state p { color: #c8ccd4; }
        html[data-theme="dark"] .empty-state strong { color: #e8eaed; }

        /* Modals */
        html[data-theme="dark"] .modal-card { background: #1a1d27; }
        html[data-theme="dark"] .modal-confirm { background: #1a1d27; }
        html[data-theme="dark"] .modal-return-btn { background: #242733; color: #e8eaed; }
        html[data-theme="dark"] .modal-return-btn:hover { background: #2a2d3a; }
        html[data-theme="dark"] .modal-label { background: #242733; color: #e8eaed; }
        html[data-theme="dark"] .step-tab { background: #242733; color: #e8eaed; }
        html[data-theme="dark"] .step-tab:hover:not(.active) { background: #2a2d3a; }
        html[data-theme="dark"] .step-tab.active { background: #e8eaed; color: #111111; }
        html[data-theme="dark"] .modal-card.has-sticky-footer .modal-actions-sticky { background: #1a1d27; border-top-color: rgba(255,255,255,0.06); }
        html[data-theme="dark"] .product-dropdown { background: #242733; border-color: rgba(255,255,255,0.08); box-shadow: 0 16px 40px rgba(0,0,0,0.4); }
        html[data-theme="dark"] .product-dropdown-item { color: #e8eaed; }
        html[data-theme="dark"] .product-dropdown-item:hover,
        html[data-theme="dark"] .product-dropdown-item.active { background: #2a2d3a; color: #ffffff; }

        /* Order total bar */
        html[data-theme="dark"] .order-total-bar { background: #242733; color: #e8eaed; }
        html[data-theme="dark"] .order-total-bar span:last-child { color: #e8eaed; }

        /* User dropdown */
        html[data-theme="dark"] .user-dropdown-toggle { background: #242733; color: #e8eaed; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        html[data-theme="dark"] .user-dropdown-toggle:hover { background: #2a2d3a; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
        html[data-theme="dark"] .user-dropdown-menu { background: #242733; border-color: rgba(255,255,255,0.08); box-shadow: 0 16px 40px rgba(0,0,0,0.4); }
        html[data-theme="dark"] .user-menu-header { border-bottom-color: rgba(255,255,255,0.06); }
        html[data-theme="dark"] .user-menu-header .user-name { color: #e8eaed; }
        html[data-theme="dark"] .user-menu-header .user-role { color: #9aa0b0; }
        html[data-theme="dark"] .user-menu-item { color: #c8ccd4; }
        html[data-theme="dark"] .user-menu-item:hover { background: #2a2d3a; color: #e8eaed; }
        html[data-theme="dark"] .user-menu-item.text-danger { color: #f87171; }
        html[data-theme="dark"] .user-menu-item.text-danger:hover { background: rgba(153,27,27,0.15); color: #fca5a5; }

        /* Flash alerts */
        html[data-theme="dark"] .success-alert,
        html[data-theme="dark"] .flash-alert.success-alert { background: rgba(22,101,52,0.2); color: #4ade80; border-color: rgba(74,222,128,0.3); }
        html[data-theme="dark"] .error-alert,
        html[data-theme="dark"] .flash-alert.error-alert { background: rgba(153,27,27,0.2); color: #f87171; border-color: rgba(248,113,113,0.3); }

        /* Orders count badge */
        html[data-theme="dark"] .orders-count { background: #242733; color: #e8eaed; }

        /* Receipt panel & related */
        html[data-theme="dark"] .receipt-panel { background: #1a1d27; }
        html[data-theme="dark"] .receipt-box { background: #242733; }
        html[data-theme="dark"] .receipt-box strong { color: #e8eaed; }
        html[data-theme="dark"] .receipt-header-row { border-bottom-color: rgba(255,255,255,0.08); }
        html[data-theme="dark"] .order-meta-block { background: #242733; }
        html[data-theme="dark"] .receipt-card { background: #242733; border-color: rgba(255,255,255,0.08); }
        html[data-theme="dark"] .ship-card { background: #1f2230; border-color: rgba(255,255,255,0.1); }
        html[data-theme="dark"] .receipt-total-box { background: #e8eaed; color: #111111; }
        html[data-theme="dark"] .receipt-items th { color: #9aa0b0; border-bottom-color: rgba(255,255,255,0.08); }
        html[data-theme="dark"] .receipt-items td { color: #e8eaed; border-bottom-color: rgba(255,255,255,0.06); }

        /* Row action buttons in dark mode */
        html[data-theme="dark"] .row-actions .btn-secondary { background: #242733; color: #e8eaed; }
        html[data-theme="dark"] .row-actions .btn-secondary:hover { background: #2a2d3a; }
        html[data-theme="dark"] .row-actions .btn-danger { background: #e8eaed; color: #111111; }
        html[data-theme="dark"] .row-actions .btn-danger:hover { background: #ffffff; }
        html[data-theme="dark"] .row-actions .btn-primary { background: #242733; color: #e8eaed; }
        html[data-theme="dark"] .row-actions .btn-primary:hover { background: #2a2d3a; }

        /* Theme toggle button */
        .theme-toggle-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all .15s ease;
        }
        .theme-toggle-item:hover { background: #f4f4f5; }
        html[data-theme="dark"] .theme-toggle-item:hover { background: #2a2d3a; }
        .theme-toggle-item .toggle-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #334155;
        }
        html[data-theme="dark"] .theme-toggle-item .toggle-label { color: #c8ccd4; }
        .theme-switch {
            position: relative;
            width: 44px;
            height: 24px;
            background: #d1d5db;
            border-radius: 999px;
            cursor: pointer;
            transition: background .3s ease;
            border: none;
            padding: 0;
            flex-shrink: 0;
        }
        .theme-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            transition: transform .3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        html[data-theme="dark"] .theme-switch { background: #60a5fa; }
        html[data-theme="dark"] .theme-switch::after { transform: translateX(20px); }

        @media print {
            body { background: #fff; }
            .no-print, .page-shell > :not(.receipt-panel) { display: none !important; }
            .receipt-panel { box-shadow: none; border-radius: 0; margin: 0; }
        }
    </style>
</head>
<script>
    // Apply theme before paint to prevent flash of wrong theme
    (function() {
        var theme = localStorage.getItem('tp-theme');
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    })();
</script>
<body>
    <div class="flash-container">
        @if(session('success'))
            <div class="flash-alert success-alert" role="alert">
                {{ session('success') }}
                <button type="button" class="flash-dismiss" aria-label="Dismiss">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-alert error-alert" role="alert">
                {{ session('error') }}
                <button type="button" class="flash-dismiss" aria-label="Dismiss">&times;</button>
            </div>
        @endif
    </div>

    <div class="page-shell">
        @yield('content')
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function removeAlert(alert) {
            alert.classList.add('closing');
            alert.addEventListener('animationend', function() {
                if (alert.isConnected) alert.remove();
            });
        }
        document.querySelectorAll('.flash-dismiss').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const alert = btn.closest('.flash-alert');
                if (alert) removeAlert(alert);
            });
        });
        document.querySelectorAll('.flash-alert').forEach(function (alert) {
            setTimeout(function () {
                if (alert.isConnected) removeAlert(alert);
            }, 5000);
        });

        document.addEventListener('click', function(e) {
            document.querySelectorAll('.user-dropdown-container.open').forEach(function(container) {
                if (!container.contains(e.target)) {
                    container.classList.remove('open');
                }
            });
        });
    });

    function toggleUserMenu(btn, event) {
        event.stopPropagation();
        const container = btn.closest('.user-dropdown-container');
        
        // Close others
        document.querySelectorAll('.user-dropdown-container.open').forEach(function(c) {
            if (c !== container) c.classList.remove('open');
        });

        container.classList.toggle('open');
    }

    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.getAttribute('data-theme') === 'dark';
        if (isDark) {
            html.removeAttribute('data-theme');
            localStorage.setItem('tp-theme', 'light');
        } else {
            html.setAttribute('data-theme', 'dark');
            localStorage.setItem('tp-theme', 'dark');
        }
    }
    </script>
    @stack('scripts')
</body>
</html>
