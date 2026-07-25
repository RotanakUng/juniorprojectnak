<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bulk Print Receipts ({{ $orders->count() }})</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --surface: #ffffff;
            --background: #f8fafc;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; }

        body { 
            margin: 0; 
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif; 
            background: var(--background); 
            color: var(--text-main); 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }

        /* ===== WEB / SCREEN STYLES ===== */
        .bulk-header-bar {
            max-width: 780px;
            margin: 24px auto 0;
            padding: 16px 24px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bulk-header-bar h1 {
            font-size: 1.1rem;
            margin: 0;
            font-weight: 800;
        }

        .bulk-header-bar button {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .receipt-panel { 
            width: min(100%, 780px); 
            margin: 24px auto; 
            background: var(--surface); 
            border-radius: 24px; 
            padding: 32px; 
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .receipt-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border);
        }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 14px;
        }

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

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            font-weight: 700;
        }

        .label-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
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
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        .meta-value {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .receipt-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 20px;
        }

        .receipt-card {
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
            background: #ffffff;
            position: relative;
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

        .ship-body {
            gap: 14px;
        }

        .field-block {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .field-label {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.06em;
        }

        .field-value {
            color: var(--text-main);
        }

        .name-value, .phone-value, .address-value { 
            font-size: 1.12rem; 
            font-weight: 800; 
            line-height: 1.35; 
            color: var(--text-main); 
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
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .badge-value {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 800;
            width: fit-content;
        }

        .zone-value {
            background: #2563eb;
        }

        .receipt-table-wrapper {
            width: 100%;
        }

        .receipt-items {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-items th {
            text-align: left;
            padding: 12px 14px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
        }

        .receipt-items td {
            padding: 14px 14px;
            font-size: 0.95rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        .col-qty { text-align: center; }
        .col-price, .col-total { text-align: right; }

        .receipt-total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0f172a;
            color: #ffffff;
            padding: 20px 24px;
            border-radius: 18px;
        }

        .total-title {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .total-price {
            font-size: 1.35rem;
            font-weight: 900;
        }

        /* =========================================================
           80mm × 80mm BATCH PRINT STYLES (PAGE BREAK AFTER EACH LABEL)
           ========================================================= */
        @media print { 
            @page {
                size: 80mm 80mm;
                margin: 0;
            }

            .bulk-header-bar {
                display: none !important;
            }

            body { 
                background: #ffffff !important; 
                color: #000000 !important;
                font-family: 'Inter', 'Plus Jakarta Sans', Arial, sans-serif !important;
                font-size: 8px !important;
                line-height: 1.2 !important;
            } 

            .receipt-panel { 
                box-shadow: none !important; 
                margin: 0 !important; 
                border-radius: 0 !important; 
                padding: 2mm 2.5mm !important;
                width: 80mm !important;
                height: 80mm !important;
                max-width: 80mm !important;
                max-height: 80mm !important;
                box-sizing: border-box !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                overflow: hidden !important;
                border: 1.2px solid #000000 !important;
                gap: 0 !important;

                /* Force page break after each 80mm label */
                page-break-after: always !important;
                break-after: page !important;
            }

            .receipt-panel:last-child {
                page-break-after: avoid !important;
                break-after: avoid !important;
            }

            .receipt-header-row {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding-bottom: 2px !important;
                border-bottom: 1.5px solid #000000 !important;
                margin-bottom: 2px !important;
                flex-shrink: 0 !important;
            }

            .brand-block {
                display: flex !important;
                align-items: center !important;
                gap: 4px !important;
            }

            .receipt-logo {
                width: 14px !important;
                height: 14px !important;
                border-radius: 3px !important;
            }

            .brand-name {
                font-size: 7.5px !important;
                font-weight: 800 !important;
                color: #000000 !important;
                letter-spacing: 0.04em !important;
            }

            .label-title {
                display: none !important;
            }

            .order-meta-block {
                background: none !important;
                padding: 0 !important;
                border-radius: 0 !important;
                gap: 8px !important;
                display: flex !important;
            }

            .meta-label {
                font-size: 6px !important;
                font-weight: 700 !important;
                color: #333333 !important;
            }

            .meta-value {
                font-size: 8.5px !important;
                font-weight: 900 !important;
                color: #000000 !important;
            }

            .receipt-grid {
                display: grid !important;
                grid-template-columns: 68% 30% !important;
                gap: 2% !important;
                margin-bottom: 2px !important;
                flex-shrink: 0 !important;
            }

            .receipt-card {
                border: 1.2px solid #000000 !important;
                border-radius: 4px !important;
                padding: 3px 4px !important;
                background: #ffffff !important;
            }

            .card-header-tag {
                font-size: 5.8px !important;
                font-weight: 900 !important;
                letter-spacing: 0.05em !important;
                margin-bottom: 2px !important;
                color: #000000 !important;
                background: none !important;
                padding: 0 0 1px 0 !important;
                border-bottom: 1px solid #000000 !important;
                display: block !important;
            }

            .ship-body {
                gap: 2px !important;
            }

            .field-block {
                margin-bottom: 2px !important;
                display: flex !important;
                flex-direction: column !important;
            }

            .field-label {
                font-size: 5.5px !important;
                font-weight: 800 !important;
                color: #444444 !important;
                letter-spacing: 0.05em !important;
                display: block !important;
                margin-bottom: 0px !important;
            }

            .name-value, .phone-value, .address-value { 
                font-size: 11.5px !important; 
                font-weight: 900 !important; 
                line-height: 1.2 !important;
                color: #000000 !important;
                display: block !important;
                letter-spacing: 0.01em !important;
            }

            .details-card {
                border: 1.2px solid #000000 !important;
                border-radius: 4px !important;
                padding: 3px 4px !important;
                background: #ffffff !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
            }

            .details-grid {
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-around !important;
                flex: 1 !important;
                margin-top: 2px !important;
            }

            .detail-cell {
                display: flex !important;
                flex-direction: row !important;
                align-items: baseline !important;
                justify-content: space-between !important;
                padding: 1px 0 !important;
            }

            .info-label {
                font-size: 6.8px !important;
                font-weight: 800 !important;
                color: #333333 !important;
                letter-spacing: 0.04em !important;
                line-height: 1 !important;
            }

            .info-value {
                font-size: 11.5px !important;
                font-weight: 900 !important;
                color: #000000 !important;
                text-align: right !important;
                line-height: 1 !important;
            }

            .badge-value {
                background: #000000 !important;
                color: #ffffff !important;
                padding: 1.5px 6px !important;
                border-radius: 3px !important;
                font-size: 8.5px !important;
                font-weight: 900 !important;
                width: auto !important;
                min-width: 34px !important;
                text-align: center !important;
                display: inline-block !important;
            }

            .receipt-table-wrapper {
                flex: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                margin: 2px 0 !important;
                overflow: hidden !important;
            }

            .receipt-items {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            .receipt-items th {
                padding: 1.5px 2px !important;
                font-size: 7px !important;
                font-weight: 800 !important;
                color: #000000 !important;
                border-bottom: 1.2px solid #000000 !important;
            }

            .receipt-items td {
                padding: 2px 2px !important;
                font-size: 8px !important;
                color: #000000 !important;
                border-bottom: 0.5px solid #888888 !important;
            }

            .receipt-total-box {
                background: #000000 !important;
                color: #ffffff !important;
                padding: 4px 6px !important;
                border-radius: 3px !important;
                margin-top: auto !important;
                flex-shrink: 0 !important;
            }

            .total-title {
                font-size: 8px !important;
                font-weight: 800 !important;
            }

            .total-price {
                font-size: 12px !important;
                font-weight: 900 !important;
            }
        }
    </style>
</head>
<body>
    <div class="bulk-header-bar no-print">
        <h1>Batch Printing {{ $orders->count() }} Labels</h1>
        <button type="button" onclick="window.print()">Print All {{ $orders->count() }} Labels</button>
    </div>

    @foreach($orders as $order)
        @include('orders.partials.receipt', ['order' => $order])
    @endforeach

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.print();
        });
    </script>
</body>
</html>
