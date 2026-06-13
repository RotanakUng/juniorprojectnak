<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Transcent Profumo')</title>
    <style>
        :root {
            color-scheme: light;
            --font: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
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
            font-family: var(--font);
            color: var(--text);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text);
            background: #f5f5f5;
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
            border-radius: 999px;
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
            background: var(--surface);
            padding: 22px 28px;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border);
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
            border: 2px solid #111111;
        }
        .brand-title h1 {
            margin: 0;
            font-size: clamp(1.9rem, 2.1vw, 2.9rem);
            letter-spacing: -0.04em;
            font-weight: 800;
            color: #111111;
        }
        .brand-title p { margin: 6px 0 0; color: var(--text-muted); font-size: 0.98rem; font-weight: 500; }
        .top-nav { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; margin-top: 0; }
        .nav-tabs {
            display: inline-flex;
            gap: 6px;
            background: #f4f4f5;
            border-radius: 999px;
            padding: 6px;
            border: 1px solid var(--border);
        }
        .nav-link {
            padding: 12px 22px;
            border-radius: 999px;
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
            width: 52px;
            height: 52px;
            border-radius: 999px;
            background: #f4f4f5;
            color: #111111;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all .2s ease;
        }
        .user-dropdown-toggle:hover { background: #e5e5e5; }
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
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
            display: block;
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
        .status-dropdown .status-pill { display: inline-flex; align-items: center; justify-content: center; width: 100%; min-height: 48px; padding: 14px 18px; border-radius: 999px; background: #fafafa; color: #111111; box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.12); cursor: pointer; transition: background .2s ease, box-shadow .2s ease; white-space: nowrap; font-weight: 700; }
        .status-dropdown .status-pill:hover { background: #f0f0f0; box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.2); }
        .status-dropdown .status-pill .dropdown-arrow { margin-left: 10px; font-size: 1rem; }
        .status-dropdown .status-menu { position: absolute; top: calc(100% + 8px); left: 0; min-width: 220px; width: max-content; max-width: 100vw; background: #ffffff; border: 1px solid #dbe3ef; border-radius: 16px; box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18); padding: 8px; z-index: 10000; display: none; }
        .status-dropdown.open .status-menu { display: block; }
        .status-option { width: 100%; border: none; background: transparent; padding: 12px 14px; border-radius: 12px; text-align: left; cursor: pointer; font-weight: 600; color: #0f172a; transition: background .16s ease, color .16s ease; }
        .status-option:hover, .status-option:focus { background: #f4f4f5; color: #111111; }
        .status-pill-text { display: inline-block; white-space: nowrap; }
        .status-option { white-space: nowrap; }
        .status-dropdown .status-pill.status-pill-not-yet-in-progress, .status-dropdown .status-pill.status-pill-not-yet { color: #9a3412; background: #fff7ed; border: 1px solid #fed7aa; box-shadow: none; }
        .status-dropdown .status-pill.status-pill-in-progress { color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; box-shadow: none; }
        .status-dropdown .status-pill.status-pill-completed { color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; box-shadow: none; }
        .status-dropdown .status-pill.status-pill-cancelled { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; box-shadow: none; }
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
        .orders-table td.col-total, .orders-table th:nth-child(4) { text-align: right; white-space: nowrap; }
        .orders-table tbody tr:hover td { background: #fafafa; }
        .orders-table tbody tr:last-child td { border-bottom: none; }
        .orders-table tbody tr:last-child td:first-child { border-bottom-left-radius: 28px; }
        .orders-table tbody tr:last-child td:last-child { border-bottom-right-radius: 28px; }
        .orders-table td .row-actions { display: grid; grid-template-columns: repeat(2, 80px); gap: 10px; align-items: center; justify-content: start; }
        .status-badge { display: inline-flex; align-items: center; padding: 8px 14px; border-radius: 999px; font-size: 0.88rem; font-weight: 700; white-space: nowrap; }
        .status-badge-not-yet-in-progress, .status-badge-not-yet { color: #9a3412; background: #fff7ed; border: 1px solid #fed7aa; }
        .status-badge-in-progress { color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; }
        .status-badge-completed { color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; }
        .status-badge-cancelled { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; }
        .row-actions form { display: inline-flex; margin: 0; }
        .row-actions .btn {
            font-size: 0.92rem;
            min-height: 44px;
            height: 44px;
            width: 80px;
            min-width: 80px;
            padding: 0;
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
            border-radius: 999px;
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
        .modal-backdrop { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 30; }
        .modal-container { position: fixed; inset: 0; z-index: 40; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; overflow-y: auto; }
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
            border-radius: 999px;
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
            border-radius: 999px;
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
            border-radius: 999px;
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
        .row-actions button { border-radius: 999px; }
        .receipt-panel { background: #fff; border-radius: 28px; padding: 28px; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.12); }
        .receipt-top { display: flex; flex-wrap: wrap; gap: 18px; align-items: center; justify-content: space-between; margin-bottom: 26px; }
        .receipt-badge { display: inline-flex; align-items: center; gap: 10px; padding: 8px 14px; border-radius: 999px; font-weight: 700; font-size: 0.9rem; white-space: nowrap; }
        .receipt-badge.badge-red, .badge-red { color: #9a3412; background: #fff7ed; border: 1px solid #fed7aa; }
        .receipt-badge.badge-orange, .badge-orange { color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; }
        .receipt-badge.badge-green, .badge-green { color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; }
        .receipt-badge.badge-gray, .badge-gray { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; }
        .receipt-brand-row { display: flex; gap: 14px; align-items: center; }
        .receipt-eyebrow { margin: 0; font-size: 0.85rem; color: #4b5563; text-transform: uppercase; letter-spacing: 0.12em; }
        .receipt-title { margin: 6px 0 0; }
        .receipt-box-label { margin: 0 0 8px; }
        .receipt-box-label.spaced { margin: 8px 0 0; }
        .table-scroll { overflow: visible; -webkit-overflow-scrolling: touch; }
        body.modal-open { overflow: hidden; }
        .receipt-logo {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: #111111;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
        }
        .receipt-section { display: grid; gap: 18px; margin-bottom: 20px; }
        .receipt-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .receipt-box { padding: 20px; border-radius: 22px; background: #f8fafc; }
        .receipt-box strong { display: block; margin-top: 8px; color: #111827; }
        .receipt-items { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .receipt-items th, .receipt-items td { padding: 14px 12px; border-bottom: 1px solid #e2e8f0; }
        .receipt-items th { color: #475569; font-weight: 700; }
        .receipt-total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-radius: 22px;
            background: #111111;
            color: #fff;
            margin-top: 18px;
        }
        .receipt-footer { margin-top: 24px; color: #475569; font-size: 0.95rem; display: grid; gap: 8px; }
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
            z-index: 10000;
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
        .overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; display: grid; place-items: center; }
        .modal-confirm {
            background: #fff;
            padding: 28px;
            border-radius: 24px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--border);
            z-index: 1001;
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

        @media print {
            body { background: #fff; }
            .no-print, .page-shell > :not(.receipt-panel) { display: none !important; }
            .receipt-panel { box-shadow: none; border-radius: 0; margin: 0; }
        }
    </style>
</head>
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
    </script>
    @stack('scripts')
</body>
</html>
