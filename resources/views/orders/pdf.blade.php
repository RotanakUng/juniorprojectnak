<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Receipt PDF</title>
    <style>
        body { margin: 0; font-family: Inter, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        .receipt-panel { width: 100%; margin: 0 auto; background: #fff; border-radius: 18px; padding: 24px; }
        .receipt-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 18px; align-items: center; }
        .receipt-logo { width: 48px; height: 48px; border-radius: 16px; background: #111111; display: grid; place-items: center; color: #fff; font-size: 1.2rem; font-weight: 800; }
        .receipt-badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 8px 14px; background: #f3f4f6; color: #1f2937; font-weight: 700; }
        .receipt-section { display: grid; gap: 16px; margin-top: 22px; }
        .receipt-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .receipt-box { padding: 16px; border-radius: 20px; background: #f8fafc; }
        .receipt-items { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .receipt-items th, .receipt-items td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; }
        .receipt-items th { text-align: left; color: #475569; }
        .receipt-total-box { display: flex; justify-content: space-between; align-items: center; padding: 18px; border-radius: 20px; background: #111111; color: #fff; margin-top: 18px; }
        .receipt-footer { margin-top: 22px; color: #475569; font-size: 0.95rem; line-height: 1.7; }
    </style>
</head>
<body>
    @include('orders.partials.receipt', ['order' => $order])
</body>
</html>
