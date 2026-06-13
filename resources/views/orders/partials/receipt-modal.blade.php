<div id="receipt-modal" class="hidden no-print">
    <div class="modal-backdrop" id="receipt-backdrop" onclick="closeReceiptModal()"></div>
    <div class="modal-container">
        <div class="modal-card receipt-panel" style="max-width:840px;">
            <div class="modal-header">
                <button type="button" class="modal-return-btn" id="receipt-modal-return">← Return</button>
                <span class="modal-label">View Receipt</span>
            </div>

            <div class="receipt-top">
                <div class="receipt-brand-row">
                    <div class="receipt-logo">P</div>
                    <div>
                        <p class="receipt-eyebrow">Transcent Profumo</p>
                        <h2 class="receipt-title">Shipping Receipt</h2>
                        <p class="text-slate section-lead">Review or print the receipt for this order.</p>
                    </div>
                </div>
                <div class="receipt-badge badge-gray" id="receipt-order-status">Status</div>
            </div>

            <div class="receipt-section">
                <div class="receipt-row">
                    <div class="receipt-box">
                        <p class="text-slate receipt-box-label">Order ID</p>
                        <strong id="receipt-order-number">ORD000</strong>
                    </div>
                    <div class="receipt-box">
                        <p class="text-slate receipt-box-label">Date</p>
                        <strong id="receipt-order-date">—</strong>
                    </div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-box">
                        <p class="text-slate receipt-box-label">Customer</p>
                        <strong id="receipt-customer-name">Name</strong>
                        <p class="text-slate receipt-box-label spaced">Phone</p>
                        <strong id="receipt-customer-phone">000000000</strong>
                        <p class="text-slate receipt-box-label spaced">Address</p>
                        <strong id="receipt-customer-address">Address details</strong>
                    </div>
                    <div class="receipt-box">
                        <p class="text-slate receipt-box-label">Payment</p>
                        <strong id="receipt-payment-type">ABA</strong>
                        <p class="text-slate receipt-box-label spaced">Payment Status</p>
                        <strong id="receipt-payment-status">Paid</strong>
                        <p class="text-slate receipt-box-label spaced">Delivery</p>
                        <strong id="receipt-delivery-type">WALK IN</strong>
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
                    <tbody id="receipt-items-body"></tbody>
                </table>
            </div>

            <div class="receipt-total-box">
                <span>Total</span>
                <strong id="receipt-total-value">$0.00</strong>
            </div>

            <div class="receipt-footer">
                <div>123 Scent Avenue, City</div>
                <div>Phone: 011 234 5678</div>
                <div>Instagram: @perfume_store</div>
            </div>

            <div class="modal-actions">
                <a id="receipt-pdf-link" class="btn btn-secondary btn-small" target="_blank">Download PDF</a>
                <button type="button" id="receipt-print-link" class="btn btn-primary btn-small" onclick="printReceipt()">Print Receipt</button>
            </div>
        </div>
    </div>
</div>
