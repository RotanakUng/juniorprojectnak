<div class="receipt-panel">
    {{-- ===== HEADER: Brand + Title + Order Meta ===== --}}
    <div class="receipt-header-row">
        <div class="brand-block">
            <div class="receipt-logo">
                <img src="{{ asset('431219605_1100922654446504_1462438396502192723_n.jpg') }}" alt="Logo">
            </div>
            <div class="brand-text">
                <span class="brand-name">Transcent Profumo</span>
                <span class="label-title">Shipping Receipt</span>
            </div>
        </div>
        <div class="order-meta-block">
            <div class="meta-item">
                <span class="meta-label">ORDER ID</span>
                <strong class="meta-value">{{ $order->order_number }}</strong>
            </div>
            <div class="meta-item">
                <span class="meta-label">DATE</span>
                <span class="meta-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- ===== MAIN GRID: Ship To (Left) + Delivery Details (Right) ===== --}}
    <div class="receipt-grid">
        {{-- SHIP TO HERO BOX (Vertical stack for max readability) --}}
        <div class="receipt-card ship-card">
            <div class="card-header-tag">SHIP TO RECIPIENT</div>
            <div class="card-body ship-body">
                <div class="field-block">
                    <span class="field-label">CUSTOMER NAME</span>
                    <strong class="field-value key-detail name-value">{{ $order->customer_name }}</strong>
                </div>
                <div class="field-block">
                    <span class="field-label">PHONE NUMBER</span>
                    <strong class="field-value key-detail phone-value">{{ $order->phone }}</strong>
                </div>
                <div class="field-block">
                    <span class="field-label">DELIVERY ADDRESS</span>
                    <strong class="field-value key-detail address-value">{{ $order->address }}</strong>
                </div>
            </div>
        </div>

        {{-- DELIVERY & PAYMENT DETAILS BOX --}}
        <div class="receipt-card details-card">
            <div class="card-header-tag">DELIVERY & PAYMENT</div>
            <div class="card-body details-grid">
                <div class="detail-cell">
                    <span class="info-label">Delivery</span>
                    <strong class="info-value">{{ $order->delivery_type }}</strong>
                </div>
                <div class="detail-cell">
                    <span class="info-label">Payment</span>
                    <strong class="info-value">{{ $order->payment_type }}</strong>
                </div>
                <div class="detail-cell">
                    <span class="info-label">Status</span>
                    <strong class="info-value status-value">{{ $order->payment_status }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ITEMS TABLE ===== --}}
    <div class="receipt-table-wrapper">
        <table class="receipt-items">
            <thead>
                <tr>
                    <th class="col-item">Item</th>
                    <th class="col-qty">Qty</th>
                    <th class="col-price">Price</th>
                    <th class="col-total">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td class="col-item">{{ $item->product_name }}</td>
                        <td class="col-qty">{{ $item->quantity }}</td>
                        <td class="col-price">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="col-total">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== TOTAL BAR ===== --}}
    <div class="receipt-total-box">
        <span class="total-title">Total Amount</span>
        <strong class="total-price">
            @if($order->payment_status === 'Paid')
                PAID
            @else
                ${{ number_format($order->total_price, 2) }}
            @endif
        </strong>
    </div>
</div>
