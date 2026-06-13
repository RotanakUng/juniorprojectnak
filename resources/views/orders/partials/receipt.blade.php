<div class="receipt-panel">
    <div class="receipt-top">
        <div style="display:flex; gap:14px; align-items:center;">
            <div class="receipt-logo">P</div>
            <div>
                <p style="margin:0; font-size:0.85rem; color:#4b5563; text-transform:uppercase; letter-spacing:0.12em;">Transcent Profumo</p>
                <h2 style="margin:6px 0 0;">Shipping Receipt</h2>
                <p class="text-slate" style="margin:6px 0 0;">Review or print the receipt for this order.</p>
            </div>
        </div>
        <div class="receipt-badge status-badge status-badge-{{ strtolower(str_replace(' ', '-', $order->status)) }}">{{ $order->status }}</div>
    </div>

    <div class="receipt-section">
        <div class="receipt-row">
            <div class="receipt-box">
                <p class="text-slate" style="margin:0 0 8px;">Order ID</p>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div class="receipt-box">
                <p class="text-slate" style="margin:0 0 8px;">Date</p>
                <strong>{{ $order->created_at->format('M d, Y H:i') }}</strong>
            </div>
        </div>
        <div class="receipt-row">
            <div class="receipt-box">
                <p class="text-slate" style="margin:0 0 8px;">Customer</p>
                <strong>{{ $order->customer_name }}</strong>
                <p class="text-slate" style="margin:8px 0 0;">Phone</p>
                <strong>{{ $order->phone }}</strong>
                <p class="text-slate" style="margin:8px 0 0;">Address</p>
                <strong>{{ $order->address }}</strong>
            </div>
            <div class="receipt-box">
                <p class="text-slate" style="margin:0 0 8px;">Payment</p>
                <strong>{{ $order->payment_type }}</strong>
                <p class="text-slate" style="margin:8px 0 0;">Payment Status</p>
                <strong>{{ $order->payment_status }}</strong>
                <p class="text-slate" style="margin:8px 0 0;">Delivery</p>
                <strong>{{ $order->delivery_type }}</strong>
            </div>
        </div>
    </div>

    <div class="receipt-section">
        <table class="receipt-items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="receipt-total-box">
        <span>Total</span>
        <strong>${{ number_format($order->total_price, 2) }}</strong>
    </div>

    <div class="receipt-footer">
        <div>123 Scent Avenue, City</div>
        <div>Phone: 011 234 5678</div>
        <div>Instagram: @perfume_store</div>
    </div>
</div>
