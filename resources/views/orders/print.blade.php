<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Print Receipt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-not-yet: #fff7ed; --text-not-yet: #9a3412; --border-not-yet: #fed7aa;
            --color-in-progress: #eff6ff; --text-in-progress: #1e40af; --border-in-progress: #bfdbfe;
            --color-completed: #f0fdf4; --text-completed: #166534; --border-completed: #bbf7d0;
            --color-cancelled: #fef2f2; --text-cancelled: #991b1b; --border-cancelled: #fecaca;
        }
        body { 
            margin: 0; 
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif; 
            background: #f8fafc; 
            color: #111827; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }
        .receipt-panel { width: min(100%, 920px); margin: 24px auto; background: #fff; border-radius: 28px; padding: 28px; }
        .receipt-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 18px; align-items: flex-start; }
        .receipt-logo { width: 52px; height: 52px; border-radius: 18px; background: #111111; display: grid; place-items: center; color: #fff; font-size: 1.25rem; font-weight: 800; }
        
        .receipt-badge { 
            display: inline-flex; 
            align-items: center; 
            border-radius: 999px; 
            padding: 8px 14px; 
            font-weight: 700; 
            font-size: 0.88rem;
            white-space: nowrap;
        }
        .status-badge-not-yet-in-progress, .status-badge-not-yet { color: var(--text-not-yet); background: var(--color-not-yet); border: 1px solid var(--border-not-yet); }
        .status-badge-in-progress { color: var(--text-in-progress); background: var(--color-in-progress); border: 1px solid var(--border-in-progress); }
        .status-badge-completed { color: var(--text-completed); background: var(--color-completed); border: 1px solid var(--border-completed); }
        .status-badge-cancelled { color: var(--text-cancelled); background: var(--color-cancelled); border: 1px solid var(--border-cancelled); }

        .receipt-section { display: grid; gap: 18px; margin-top: 32px; }
        .receipt-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .receipt-box { padding: 20px; border-radius: 22px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .receipt-items { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .receipt-items th, .receipt-items td { padding: 16px 12px; border-bottom: 1px solid #e2e8f0; }
        .receipt-items th { text-align: left; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; }
        .receipt-items td { font-size: 0.95rem; }
        .receipt-total-box { display: flex; justify-content: space-between; align-items: center; padding: 24px; border-radius: 22px; background: #111111; color: #fff; margin-top: 24px; font-size: 1.1rem; }
        .receipt-footer { margin-top: 32px; color: #64748b; font-size: 0.95rem; line-height: 1.8; text-align: center; }
        
        .text-slate { color: #64748b; }

        /* --- 80mm x 80mm SQUARE PRINT STYLES --- */
        @media print { 
            @page {
                size: 80mm 80mm; /* Strict square dimensions */
                margin: 0;       /* No default browser margins */
            }
            body { 
                background: #fff; 
                color: #000;
                font-size: 11px;
                line-height: 1.2;
            } 
            .receipt-panel { 
                box-shadow: none; 
                margin: 0; 
                border-radius: 0; 
                padding: 3mm 4mm; /* Compact padding */
                width: 80mm !important;
                height: 80mm !important;
                max-width: 80mm !important;
                max-height: 80mm !important;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                justify-content: space-between; /* Fits layout vertically */
                overflow: hidden; /* Prevents spilling to 2nd label */
            }
            .receipt-top {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                gap: 8px;
            }
            .receipt-top div:first-child {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .receipt-top p, .receipt-top .text-slate {
                display: none !important; /* Hide description subtitles to save height */
            }
            .receipt-logo {
                width: 32px !important;
                height: 32px !important;
                border-radius: 8px !important;
            }
            .receipt-top h2 {
                font-size: 13px !important;
                margin: 0 !important;
            }
            .receipt-section {
                margin-top: 4px !important;
                gap: 6px !important;
            }
            /* Make key details columns stack vertically or fit nicely */
            .receipt-row {
                grid-template-columns: 1.2fr 0.8fr !important;
                gap: 6px !important;
            }
            .receipt-box {
                padding: 6px !important;
                border-radius: 8px !important;
                background: #fff !important;
                border: 1px solid #000 !important;
                font-size: 9.5px !important;
            }
            .receipt-box strong {
                font-size: 10px !important;
                display: block;
            }
            .receipt-box p.text-slate {
                margin: 0 0 2px 0 !important;
                font-size: 8px !important;
                font-weight: bold;
                text-transform: uppercase;
                color: #000 !important;
            }
            .receipt-box .spaced {
                margin-top: 4px !important;
            }
            /* Table formatting */
            .receipt-items {
                margin-top: 4px !important;
            }
            .receipt-items th, .receipt-items td {
                padding: 3px 2px !important;
                font-size: 9px !important;
                border-bottom: 1px solid #000 !important;
            }
            .receipt-items th {
                color: #000 !important;
                font-weight: bold;
            }
            /* Total section: monochrome and simple */
            .receipt-total-box {
                background: #fff !important;
                color: #000 !important;
                border-top: 1.5px dashed #000 !important;
                border-bottom: 1.5px dashed #000 !important;
                border-radius: 0 !important;
                padding: 6px 0 !important;
                margin-top: 6px !important;
                font-size: 11px !important;
                font-weight: bold;
            }
            /* Hide non-essential brand footer (saves critical space) */
            .receipt-footer {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    @include('orders.partials.receipt', ['order' => $order])
    <script>window.print();</script>
</body>
</html>
