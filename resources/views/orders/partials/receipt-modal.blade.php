<div id="receipt-modal" class="hidden no-print">
    <div class="modal-backdrop" id="receipt-backdrop" onclick="closeReceiptModal()"></div>
    <div class="modal-container">
        <div class="modal-card receipt-panel" style="max-width:840px;">
            <div class="modal-header">
                <button type="button" class="modal-return-btn" id="receipt-modal-return">← Return</button>
                <span class="modal-label">View Receipt</span>
            </div>

            {{-- ===== HEADER ===== --}}
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
                        <strong class="meta-value" id="receipt-order-number">ORD000</strong>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">DATE</span>
                        <span class="meta-value" id="receipt-order-date">—</span>
                    </div>
                </div>
            </div>

            {{-- ===== MAIN GRID ===== --}}
            <div class="receipt-grid">
                <div class="receipt-card ship-card">
                    <div class="card-header-tag">SHIP TO RECIPIENT</div>
                    <div class="card-body ship-body">
                        <div class="field-block">
                            <span class="field-label">CUSTOMER NAME</span>
                            <strong class="field-value key-detail name-value" id="receipt-customer-name">Name</strong>
                        </div>
                        <div class="field-block">
                            <span class="field-label">PHONE NUMBER</span>
                            <strong class="field-value key-detail phone-value" id="receipt-customer-phone">000000000</strong>
                        </div>
                        <div class="field-block">
                            <span class="field-label">DELIVERY ADDRESS</span>
                            <strong class="field-value key-detail address-value" id="receipt-customer-address">Address details</strong>
                        </div>
                    </div>
                </div>

                <div class="receipt-card details-card">
                    <div class="card-header-tag">DELIVERY & PAYMENT</div>
                    <div class="card-body details-grid">
                        <div class="detail-cell">
                            <span class="info-label">Delivery</span>
                            <strong class="badge-value" id="receipt-delivery-type">WALK IN</strong>
                        </div>
                        <div class="detail-cell">
                            <span class="info-label">Zone</span>
                            <strong class="badge-value zone-value" id="receipt-shipping-zone">In City</strong>
                        </div>
                        <div class="detail-cell">
                            <span class="info-label">Payment</span>
                            <strong class="info-value" id="receipt-payment-type">ABA</strong>
                        </div>
                        <div class="detail-cell">
                            <span class="info-label">Status</span>
                            <strong class="info-value status-value" id="receipt-payment-status">Paid</strong>
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
                    <tbody id="receipt-items-body"></tbody>
                </table>
            </div>

            {{-- ===== TOTAL BAR ===== --}}
            <div class="receipt-total-box">
                <span class="total-title">Total Amount</span>
                <strong class="total-price" id="receipt-total-value">$0.00</strong>
            </div>

            {{-- ===== MODAL ACTIONS ===== --}}
            <div class="modal-actions">
                <a id="receipt-pdf-link" class="btn btn-secondary btn-small" target="_blank">Download PDF</a>
                <button type="button" id="receipt-print-link" class="btn btn-primary btn-small" onclick="printReceipt()">Print Receipt</button>
            </div>
        </div>
    </div>
</div>
