@extends('layouts.app')

@section('title', 'Transcent Profumo · Orders')

@section('content')
    <div class="dashboard-wrapper">
        <div class="brand-panel">
            <div class="brand-row">
                @include('partials.user-menu')
                <div class="brand-chip">
                    <img src="{{ asset('431219605_1100922654446504_1462438396502192723_n.jpg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="brand-title">
                    <h1>Transcent Profumo</h1>
                    <p>Premium POS · Order Management</p>
                </div>
            </div>
            <div class="action-group">
                <a href="{{ route('products.index') }}" class="btn btn-ghost" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    <span>Manage Products</span>
                </a>
                <button id="openCreateOrderModal" type="button" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Create New Order</span>
                </button>
            </div>
        </div>

        <div class="page-card dashboard-card">
        <div class="top-nav" style="margin-bottom: 20px;">
            <div class="nav-tabs">
                <a href="{{ route('orders.index', array_merge(request()->only(['search','status']), ['tab' => 'orders'])) }}" class="nav-link {{ $tab === 'orders' ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Orders</span>
                </a>
                <a href="{{ route('orders.index', array_merge(request()->only(['search','status']), ['tab' => 'spreadsheet'])) }}" class="nav-link {{ $tab === 'spreadsheet' ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Spreadsheet</span>
                </a>
            </div>
            <div class="action-group">
                <form action="{{ route('orders.export') }}" method="GET" class="no-print">
                    @if(request()->has('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request()->has('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if(request()->has('date'))
                        <input type="hidden" name="date" value="{{ request('date') }}">
                    @endif
                    <button type="submit" class="btn btn-secondary btn-small" style="display: inline-flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Download CSV</span>
                    </button>
                </form>
                <a href="https://docs.google.com/spreadsheets" target="_blank" class="btn btn-secondary btn-small" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span>Open Google Sheets</span>
                </a>
            </div>
        </div>

        <div class="card-panel">
            <div class="panel-row toolbar-row">
                <div class="toolbar-filters">
                    <form action="{{ route('orders.index') }}" method="GET" class="search-form">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <input type="text" name="search" class="search-input" placeholder="Search by customer, order number, or status…" value="{{ $search }}">
                        <input type="date" name="date" class="search-select" value="{{ $date }}" style="flex: 0 0 180px; cursor: pointer;" onclick="this.showPicker()" onkeydown="if(event.key!=='Tab')event.preventDefault()">
                        <select name="status" class="search-select">
                            <option value="all">All statuses</option>
                            @foreach($statuses as $item)
                                <option value="{{ $item }}" {{ $status === $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                @if($search || $status !== 'all' || $date)
                    <div class="filter-chip">
                        Filtered
                        @if($status !== 'all')<span>· {{ $status }}</span>@endif
                        @if($date)<span>· {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>@endif
                        @if($search)<span>· "{{ $search }}"</span>@endif
                        <a href="{{ route('orders.index', ['tab' => $tab]) }}">Clear</a>
                    </div>
                @endif
            </div>

            @if($tab === 'spreadsheet')
                <div class="panel-row section-header">
                    <div>
                        <h2 class="text-lg" style="margin:0;">Orders Spreadsheet</h2>
                        <p class="text-slate section-lead">Full order + item details in a spreadsheet layout.</p>
                    </div>
                    <span class="orders-count">{{ $orders->count() }} order{{ $orders->count() === 1 ? '' : 's' }}</span>
                </div>

                @if($orders->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon" aria-hidden="true">📊</div>
                        <p>No orders found.</p>
                        @if($search || $status !== 'all')
                            <p class="text-slate">Try adjusting your search or status filter.</p>
                            <a href="{{ route('orders.index', ['tab' => $tab]) }}" class="btn btn-secondary btn-small">Clear filters</a>
                        @else
                            <button type="button" class="btn btn-primary btn-small" id="empty-create-order-spreadsheet">Create New Order</button>
                        @endif
                    </div>
                @else
                    <div class="table-wrapper spreadsheet-wrapper">
                        <table class="table spreadsheet-table">
                            <thead>
                                <tr>
                                    <th class="col-order-id">Order ID</th>
                                    <th class="col-date">Date</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-customer">Customer</th>
                                    <th class="col-phone">Phone</th>
                                    <th class="col-address">Address</th>
                                    <th class="col-payment">Pay Type</th>
                                    <th class="col-payment-status">Paid</th>
                                    <th class="col-delivery">Delivery</th>
                                    <th class="col-items">Items</th>
                                    <th class="col-total">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="col-order-id">{{ $order->order_number }}</td>
                                        <td>
                                            <span class="spreadsheet-date">{{ $order->created_at->format('m/d/y') }}</span>
                                            <span class="spreadsheet-time text-slate">{{ $order->created_at->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-badge-{{ strtolower(str_replace(' ', '-', $order->status)) }}">{{ $order->status }}</span>
                                        </td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td class="col-phone">{{ $order->phone }}</td>
                                        <td>{{ $order->address }}</td>
                                        <td>{{ $order->payment_type }}</td>
                                        <td>{{ $order->payment_status }}</td>
                                        <td>{{ $order->delivery_type }}</td>
                                        <td class="col-items">
                                            <div class="item-list">
                                                @foreach($order->orderItems as $item)
                                                    <div>{{ $item->product_name }} - {{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }} = ${{ number_format($item->total_price, 2) }}</div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="col-total">${{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="order-total-bar" style="margin-top: 18px;">
                        <span style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Total Payment
                        </span>
                        <span>${{ number_format($orders->sum('total_price'), 2) }}</span>
                    </div>
                @endif
            @else
                <div class="panel-row section-header">
                    <div>
                        <h2 class="text-lg" style="margin:0;">Orders</h2>
                        <p class="text-slate section-lead">Manage orders, update status, and print receipts.</p>
                    </div>
                    <span class="orders-count">{{ $orders->count() }} order{{ $orders->count() === 1 ? '' : 's' }}</span>
                </div>

                @if($orders->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon" aria-hidden="true">📋</div>
                        <p>No orders found.</p>
                        @if($search || $status !== 'all')
                            <p class="text-slate">Try adjusting your search or status filter.</p>
                            <a href="{{ route('orders.index', ['tab' => $tab]) }}" class="btn btn-secondary btn-small">Clear filters</a>
                        @else
                            <button type="button" class="btn btn-primary btn-small" id="empty-create-order">Create New Order</button>
                        @endif
                    </div>
                @else
                    <div class="table-wrapper">
                        <table class="table orders-table">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Order Date</th>
                                    <th>Shipping Zone</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                        <td>{{ $order->shipping_zone ?? '—' }}</td>
                                        <td>
                                            <div class="order-items-list">
                                                @foreach($order->orderItems as $item)
                                                    <div class="order-item-line">
                                                        <strong>{{ $item->product_name }}</strong> · {{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }} = ${{ number_format($item->total_price, 2) }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="col-total">${{ number_format($order->total_price, 2) }}</td>
                                        <td class="status-cell">
                                            <form action="{{ route('orders.status', $order) }}" method="POST" class="status-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $order->status }}" class="status-hidden-input">
                                                <div class="status-dropdown">
                                                    <button type="button" class="status-trigger status-pill status-pill-{{ strtolower(str_replace(' ', '-', $order->status)) }}" aria-haspopup="true" aria-expanded="false">
                                                        <span class="status-pill-text">{{ $order->status }}</span>
                                                        <span class="dropdown-arrow">▾</span>
                                                    </button>
                                                    <div class="status-menu">
                                                        @foreach($statuses as $statusOption)
                                                            <button type="button" class="status-option" data-value="{{ $statusOption }}">{{ $statusOption }}</button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="row-actions">
                                                <button type="button" class="btn btn-secondary btn-small edit-order-btn" data-order='{!! json_encode($order->load("orderItems"), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}' style="display: inline-flex; align-items: center; gap: 6px;">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4z"></path></svg>
                                                    <span>Edit</span>
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-small" onclick="openReceiptModal({{ $order->id }})" style="display: inline-flex; align-items: center; gap: 6px;">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    <span>View</span>
                                                </button>
                                                <a class="btn btn-secondary btn-small" href="{{ route('orders.print', $order) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px;">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                    <span>Print</span>
                                                </a>
                                                <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline;" class="delete-order-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-small delete-order-btn" style="display: inline-flex; align-items: center; gap: 6px;">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                        <span>Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="order-total-bar" style="margin-top: 18px;">
                        <span style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Total Payment
                        </span>
                        <span>${{ number_format($orders->sum('total_price'), 2) }}</span>
                    </div>
                @endif
            @endif
        </div>
    </div>
    </div>

    @include('orders.partials.order-modal')
    @include('orders.partials.receipt-modal')

    <div id="delete-modal-overlay" class="overlay hidden no-print">
        <div class="modal-confirm" role="dialog" aria-labelledby="delete-modal-title" aria-modal="true">
            <h3 id="delete-modal-title">Delete this order?</h3>
            <p class="text-slate section-lead">This action cannot be undone.</p>
            <div class="modal-actions">
                <button type="button" id="delete-cancel" class="btn btn-secondary">Cancel</button>
                <button type="button" id="delete-confirm" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts' )
<script>
function setBodyModalOpen(isOpen) {
    document.body.classList.toggle('modal-open', isOpen);
}

function statusBadgeClass(status) {
    const map = {
        'In Progress': 'badge-orange',
        'Completed': 'badge-green',
    };
    return map[status] || 'badge-gray';
}

function formatReceiptMoney(value) {
    return '$' + (Number(value) || 0).toFixed(2);
}

function formatReceiptDate(value) {
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '—';
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

window.openReceiptModal = async function (orderId) {
    const modal = document.getElementById('receipt-modal');
    if (!modal) return;

    try {
        const response = await fetch('/orders/' + orderId, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        if (!response.ok) throw new Error('Failed to load order');
        const order = await response.json();
        const items = order.order_items || order.orderItems || [];

        const statusEl = document.getElementById('receipt-order-status');
        if (statusEl) {
            statusEl.textContent = order.status || '—';
            statusEl.className = 'receipt-badge ' + statusBadgeClass(order.status);
        }

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value ?? '—';
        };

        setText('receipt-order-number', order.order_number);
        setText('receipt-order-date', formatReceiptDate(order.created_at));
        setText('receipt-customer-name', order.customer_name);
        setText('receipt-customer-phone', order.phone);
        setText('receipt-customer-address', order.address);
        setText('receipt-payment-type', order.payment_type);
        setText('receipt-payment-status', order.payment_status);
        setText('receipt-delivery-type', order.delivery_type);
        setText('receipt-shipping-zone', order.shipping_zone);
        setText('receipt-total-value', formatReceiptMoney(order.total_price));

        const itemsBody = document.getElementById('receipt-items-body');
        if (itemsBody) {
            itemsBody.innerHTML = items.map(function (item) {
                return '<tr>'
                    + '<td>' + (item.product_name || '') + '</td>'
                    + '<td>' + (item.quantity || 0) + '</td>'
                    + '<td>' + formatReceiptMoney(item.unit_price) + '</td>'
                    + '<td>' + formatReceiptMoney(item.total_price) + '</td>'
                    + '</tr>';
            }).join('');
        }

        const pdfLink = document.getElementById('receipt-pdf-link');
        if (pdfLink) pdfLink.href = '/orders/' + orderId + '/pdf';

        const printLink = document.getElementById('receipt-print-link');
        if (printLink) printLink.dataset.printUrl = '/orders/' + orderId + '/print';

        modal.classList.remove('hidden');
        setBodyModalOpen(true);
    } catch (error) {
        console.error(error);
        alert('Could not load the receipt. Please try again.');
    }
};

window.printReceipt = function () {
    const printLink = document.getElementById('receipt-print-link');
    const url = printLink ? printLink.dataset.printUrl : '';
    if (!url) return;

    let iframe = document.getElementById('receipt-print-iframe');
    if (!iframe) {
        iframe = document.createElement('iframe');
        iframe.id = 'receipt-print-iframe';
        iframe.style.position = 'fixed';
        iframe.style.right = '0';
        iframe.style.bottom = '0';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        document.body.appendChild(iframe);
    }
    // Append a timestamp parameter to bypass cache and guarantee print script execution on subsequent clicks
    iframe.src = url + (url.indexOf('?') !== -1 ? '&' : '?') + 't=' + Date.now();
};

document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        const statusSelect = searchForm.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.addEventListener('change', function () {
                searchForm.submit();
            });
        }

        const dateInput = searchForm.querySelector('input[name="date"]');
        if (dateInput) {
            dateInput.addEventListener('change', function () {
                searchForm.submit();
            });
        }

        const searchInput = searchForm.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchForm.submit();
                }
            });
        }
    }

    function openCreateOrder() {
        if (typeof openOrderModal === 'function') {
            openOrderModal();
        } else {
            const modal = document.getElementById('order-modal');
            if (modal) modal.classList.remove('hidden');
        }
        setBodyModalOpen(true);
    }

    const openBtn = document.getElementById('openCreateOrderModal');
    if (openBtn) openBtn.addEventListener('click', openCreateOrder);

    ['empty-create-order', 'empty-create-order-spreadsheet'].forEach(function (id) {
        const btn = document.getElementById(id);
        if (btn) btn.addEventListener('click', openCreateOrder);
    });

    // Edit buttons (use data-order JSON)
    document.querySelectorAll('.edit-order-btn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const raw = this.dataset.order || this.getAttribute('data-order');
            if(!raw) return;
            try{
                const order = JSON.parse(raw);
                if(typeof openOrderModal === 'function') openOrderModal(order);
            }catch(err){
                console.error('Invalid order JSON', err, raw);
            }
        });
    });

    // Delete flow
    let pendingDeleteForm = null;
    document.querySelectorAll('.delete-order-btn').forEach(btn=>{
        btn.addEventListener('click', function(e){
            e.preventDefault();
            pendingDeleteForm = this.closest('form');
            const overlay = document.getElementById('delete-modal-overlay');
            if(overlay) overlay.classList.remove('hidden');
        });
    });

    const deleteCancel = document.getElementById('delete-cancel');
    const deleteConfirm = document.getElementById('delete-confirm');
    function closeDeleteModal() {
        const overlay = document.getElementById('delete-modal-overlay');
        if (overlay) overlay.classList.add('hidden');
        pendingDeleteForm = null;
    }

    if (deleteCancel) deleteCancel.addEventListener('click', closeDeleteModal);
    if (deleteConfirm) deleteConfirm.addEventListener('click', function () {
        if (pendingDeleteForm) pendingDeleteForm.submit();
    });

    const deleteOverlay = document.getElementById('delete-modal-overlay');
    if (deleteOverlay) {
        deleteOverlay.addEventListener('click', function (event) {
            if (event.target === deleteOverlay) closeDeleteModal();
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;

        const deleteOverlayEl = document.getElementById('delete-modal-overlay');
        if (deleteOverlayEl && !deleteOverlayEl.classList.contains('hidden')) {
            closeDeleteModal();
            return;
        }

        const receiptModal = document.getElementById('receipt-modal');
        if (receiptModal && !receiptModal.classList.contains('hidden')) {
            if (typeof closeReceiptModal === 'function') closeReceiptModal();
            return;
        }

        const orderModal = document.getElementById('order-modal');
        if (orderModal && !orderModal.classList.contains('hidden')) {
            const orderAction = document.getElementById('order-action');
            if (typeof closeOrderModal === 'function') {
                closeOrderModal(orderAction && orderAction.value === 'store');
            }
        }
    });

    // Status dropdowns — use fixed positioning so menus aren't clipped by .table-wrapper overflow
    function resetStatusMenu(dropdown) {
        const menu = dropdown.querySelector('.status-menu');
        if (!menu) return;
        menu.classList.remove('is-fixed');
        menu.style.top = '';
        menu.style.left = '';
        menu.style.minWidth = '';
    }

    function closeStatusDropdown(dropdown) {
        dropdown.classList.remove('open');
        const triggerBtn = dropdown.querySelector('.status-trigger');
        if (triggerBtn) triggerBtn.setAttribute('aria-expanded', 'false');
        dropdown.style.zIndex = '';
        resetStatusMenu(dropdown);
    }

    function positionStatusMenu(dropdown) {
        const trigger = dropdown.querySelector('.status-trigger');
        const menu = dropdown.querySelector('.status-menu');
        if (!trigger || !menu) return;

        const rect = trigger.getBoundingClientRect();
        menu.classList.add('is-fixed');
        menu.style.left = rect.left + 'px';
        menu.style.minWidth = Math.max(rect.width, 220) + 'px';

        const menuHeight = menu.offsetHeight;
        const spaceBelow = window.innerHeight - rect.bottom;
        if (spaceBelow < menuHeight + 8 && rect.top > spaceBelow) {
            menu.style.top = (rect.top - menuHeight - 8) + 'px';
        } else {
            menu.style.top = (rect.bottom + 8) + 'px';
        }
    }

    document.querySelectorAll('.status-trigger').forEach(trigger => {
        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            const dropdown = this.closest('.status-dropdown');
            document.querySelectorAll('.status-dropdown.open').forEach(el => {
                if (el !== dropdown) closeStatusDropdown(el);
            });
            const isOpen = dropdown.classList.toggle('open');
            this.setAttribute('aria-expanded', String(isOpen));
            if (isOpen) {
                dropdown.style.zIndex = '10000';
                positionStatusMenu(dropdown);
            } else {
                closeStatusDropdown(dropdown);
            }
        });
    });

    window.addEventListener('scroll', function () {
        document.querySelectorAll('.status-dropdown.open').forEach(positionStatusMenu);
    }, true);
    window.addEventListener('resize', function () {
        document.querySelectorAll('.status-dropdown.open').forEach(positionStatusMenu);
    });

    document.querySelectorAll('.status-option').forEach(option => {
        option.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            const form = this.closest('form');
            const input = form.querySelector('input[name="status"]');
            if (!input) return;
            input.value = this.dataset.value;
            const dropdown = this.closest('.status-dropdown');
            if (dropdown) closeStatusDropdown(dropdown);
            form.submit();
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.status-dropdown')) {
            document.querySelectorAll('.status-dropdown.open').forEach(closeStatusDropdown);
        }
    });

});
</script>
@endpush

