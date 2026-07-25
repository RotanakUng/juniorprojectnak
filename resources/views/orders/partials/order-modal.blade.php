@php
    $oldItems = old('items', [['product_name' => '', 'quantity' => 1, 'unit_price' => '']]);
@endphp

<div id="order-modal" class="hidden no-print">
    <div class="modal-backdrop" onclick="closeOrderModal(true)"></div>
    <div class="modal-container">
        <div class="modal-card has-sticky-footer">
            <div class="modal-header">
                <button type="button" class="modal-return-btn" id="order-modal-return">← Return</button>
                <span class="modal-label" id="order-modal-title">Create New Order</span>
            </div>

            <div class="modal-heading">
                <h2 id="order-modal-subtitle">Complete customer details then choose perfume items.</h2>
            </div>

            <div class="step-tabs" id="order-step-nav">
                <button type="button" class="step-tab active" id="nav-step-1" onclick="showOrderStep(1)">Step 1 · Customer Info</button>
                <button type="button" class="step-tab" id="nav-step-2" onclick="showOrderStep(2)">Step 2 · Order Items</button>
            </div>

            @if($errors->any())
                <div class="error-alert">Please fix the highlighted fields and submit again.</div>
            @endif

            <form id="order-form" action="{{ route('orders.store') }}" method="POST" data-store-action="{{ route('orders.store') }}">
                @csrf
                <input type="hidden" name="_method" value="POST" id="order-method" />
                <input type="hidden" name="action" id="order-action" value="store" />
                <input type="hidden" name="current_step" id="current-step" value="1" />

                <div class="modal-form-body">

                <div id="order-step-1" class="form-step active">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="customer_name">Customer Name <span class="required-asterisk" style="color:#ef4444; font-weight:700; margin-left:2px;">*</span></label>
                            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required-asterisk" style="color:#ef4444; font-weight:700; margin-left:2px;">*</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="off">
                        </div>
                        <div class="form-group full">
                            <label for="address">Address <span class="required-asterisk" style="color:#ef4444; font-weight:700; margin-left:2px;">*</span></label>
                            <textarea id="address" name="address" required autocomplete="off">{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="payment_type">Payment Type <span class="required-asterisk" style="color:#ef4444; font-weight:700; margin-left:2px;">*</span></label>
                            <select id="payment_type" name="payment_type" required>
                                <option value="">Select payment type</option>
                                <option value="ABA" {{ old('payment_type') === 'ABA' ? 'selected' : '' }}>ABA</option>
                                <option value="COD" {{ old('payment_type') === 'COD' ? 'selected' : '' }}>COD</option>
                                <option value="CASH" {{ old('payment_type') === 'CASH' ? 'selected' : '' }}>CASH</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_status">Payment Status <span class="required-asterisk" style="color:#ef4444; font-weight:700; margin-left:2px;">*</span></label>
                            <select id="payment_status" name="payment_status" required>
                                <option value="">Select payment status</option>
                                <option value="Unpaid" {{ old('payment_status') === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="Paid" {{ old('payment_status') === 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="delivery_type">Delivery Type <span class="required-asterisk" style="color:#ef4444; font-weight:700; margin-left:2px;">*</span></label>
                            <select id="delivery_type" name="delivery_type" required>
                                <option value="">Select delivery type</option>
                                <option value="WALK IN" {{ old('delivery_type') === 'WALK IN' ? 'selected' : '' }}>WALK IN</option>
                                <option value="PICK UP" {{ old('delivery_type') === 'PICK UP' ? 'selected' : '' }}>PICK UP</option>
                                <option value="COD" {{ old('delivery_type') === 'COD' ? 'selected' : '' }}>COD</option>
                                <option value="VET" {{ old('delivery_type') === 'VET' ? 'selected' : '' }}>VET</option>
                            </select>
                        </div>
                    </div>
                </div>
                        <style>
                            .product-autocomplete { position: relative; width: 100%; }
                            .product-autocomplete input[type="text"] { width: 100%; }
                            .product-dropdown { position: fixed; background: #fff; border: 1px solid #dbe3ef; border-radius: 14px; box-shadow: 0 12px 32px rgba(15,23,42,0.14); z-index: 11500; max-height: 200px; overflow-y: auto; display: none; padding: 6px; }
                            .product-dropdown.show { display: block; }
                            .product-dropdown-item { padding: 10px 14px; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #1f2937; transition: background .12s; }
                            .product-dropdown-item:hover, .product-dropdown-item.active { background: #f4f4f5; color: #111; }
                            .product-dropdown-empty { padding: 12px 14px; color: #94a3b8; font-size: 13px; font-weight: 500; }
                        </style>
                        <script>
                        (function(){
                            window.currentStep = 1;
                            window._productList = [];
                            window._productsFetched = false;
                            function toFixed2(n){ return (Number(n)||0).toFixed(2); }

                            window.fetchProducts = function(){
                                if(window._productsFetched) return Promise.resolve(window._productList);
                                return fetch('/api/products')
                                    .then(r => r.json())
                                    .then(data => { window._productList = data; window._productsFetched = true; return data; })
                                    .catch(() => []);
                            };

                            window.initProductAutocomplete = function(wrapper){
                                if(!wrapper || wrapper.dataset.acInit) return;
                                wrapper.dataset.acInit = '1';
                                const input = wrapper.querySelector('input[name*="[product_name]"]');
                                const dropdown = wrapper.querySelector('.product-dropdown');
                                if(!input || !dropdown) return;
                                let activeIdx = -1;

                                // Move dropdown to body so it escapes all overflow clipping
                                document.body.appendChild(dropdown);

                                function getSelectedProducts(){
                                    const selected = [];
                                    document.querySelectorAll('#order-items tr').forEach(function(row){
                                        const rowInput = row.querySelector('input[name*="[product_name]"]');
                                        if(rowInput && rowInput !== input && rowInput.value.trim() !== ''){
                                            selected.push(rowInput.value.trim().toLowerCase());
                                        }
                                    });
                                    return selected;
                                }

                                function positionDropdown(){
                                    const rect = input.getBoundingClientRect();
                                    dropdown.style.top = (rect.bottom + 4) + 'px';
                                    dropdown.style.left = rect.left + 'px';
                                    dropdown.style.width = rect.width + 'px';
                                }

                                function render(filter){
                                    const list = window._productList || [];
                                    const q = (filter || '').toLowerCase();
                                    const usedProducts = getSelectedProducts();
                                    const available = list.filter(n => !usedProducts.includes(n.toLowerCase()));
                                    const matches = q ? available.filter(n => n.toLowerCase().includes(q)) : available;
                                    activeIdx = -1;
                                    if(matches.length === 0){
                                        dropdown.innerHTML = '<div class="product-dropdown-empty">' + (list.length === 0 ? 'No products yet. Add products first.' : 'No matching products.') + '</div>';
                                    } else {
                                        dropdown.innerHTML = matches.map((n, i) => '<div class="product-dropdown-item" data-index="'+i+'" data-value="'+n.replace(/"/g,'&quot;')+'">'+n.replace(/</g,'&lt;')+'</div>').join('');
                                    }
                                    positionDropdown();
                                    dropdown.classList.add('show');
                                }

                                function pick(value){
                                    input.value = value;
                                    dropdown.classList.remove('show');
                                }

                                // Reposition on scroll of modal body
                                var modalBody = input.closest('.modal-form-body');
                                if(modalBody){
                                    modalBody.addEventListener('scroll', function(){
                                        if(dropdown.classList.contains('show')) positionDropdown();
                                    });
                                }
                                // Reposition on window scroll/resize
                                window.addEventListener('scroll', function(){
                                    if(dropdown.classList.contains('show')) positionDropdown();
                                }, true);
                                window.addEventListener('resize', function(){
                                    if(dropdown.classList.contains('show')) positionDropdown();
                                });

                                input.addEventListener('focus', function(){
                                    fetchProducts().then(() => render(input.value));
                                });
                                input.addEventListener('input', function(){
                                    render(input.value);
                                });

                                input.addEventListener('keydown', function(e){
                                    const items = dropdown.querySelectorAll('.product-dropdown-item');
                                    if(!items.length) return;
                                    if(e.key === 'ArrowDown'){
                                        e.preventDefault();
                                        activeIdx = Math.min(activeIdx + 1, items.length - 1);
                                        items.forEach((el,i) => el.classList.toggle('active', i === activeIdx));
                                        items[activeIdx].scrollIntoView({block:'nearest'});
                                    } else if(e.key === 'ArrowUp'){
                                        e.preventDefault();
                                        activeIdx = Math.max(activeIdx - 1, 0);
                                        items.forEach((el,i) => el.classList.toggle('active', i === activeIdx));
                                        items[activeIdx].scrollIntoView({block:'nearest'});
                                    } else if(e.key === 'Enter' && activeIdx >= 0 && activeIdx < items.length){
                                        e.preventDefault();
                                        pick(items[activeIdx].dataset.value);
                                    } else if(e.key === 'Escape'){
                                        dropdown.classList.remove('show');
                                    }
                                });

                                dropdown.addEventListener('mousedown', function(e){
                                    const item = e.target.closest('.product-dropdown-item');
                                    if(item){ e.preventDefault(); pick(item.dataset.value); }
                                });

                                document.addEventListener('click', function(e){
                                    if(!wrapper.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.remove('show');
                                });
                            };

                            window.showOrderStep = function(step){
                                const max = 2;
                                if (step < 1) step = 1;
                                if (step > max) step = max;
                                window.currentStep = step;
                                const input = document.getElementById('current-step'); if(input) input.value = step;
                                document.querySelectorAll('.form-step').forEach(el=> el.classList.toggle('active', el.id === 'order-step-'+step));
                                const back = document.getElementById('step-back');
                                const next = document.getElementById('step-next');
                                const save = document.getElementById('step-save');
                                if(back) back.classList.toggle('hidden', step === 1);
                                if(next) next.classList.toggle('hidden', step === max);
                                if(save) save.classList.toggle('hidden', step !== max);
                                // update nav pills
                                const nav1 = document.getElementById('nav-step-1');
                                const nav2 = document.getElementById('nav-step-2');
                                if(nav1) nav1.classList.toggle('active', step === 1);
                                if(nav2) nav2.classList.toggle('active', step === 2);
                            };

                            window.bindPriceInput = function(input){
                                if(!input || input.dataset.priceBound) return;
                                input.dataset.priceBound = '1';
                                input.addEventListener('focus', function(){
                                    if(this.value === '' || Number(this.value) === 0){
                                        this.value = '';
                                    }
                                    this.select();
                                });
                                input.addEventListener('blur', function(){
                                    if(this.value === '' || Number(this.value) === 0){
                                        this.value = '';
                                    }
                                    updateRowTotal(this);
                                });
                            };

                            window.bindItemRow = function(row){
                                if(!row) return;
                                const priceEl = row.querySelector('.price-input');
                                if(priceEl) bindPriceInput(priceEl);
                            };

                            window.addOrderItem = function(){
                                const tbody = document.getElementById('order-items');
                                if(!tbody) return;
                                const index = tbody.children.length;
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td><div class="product-autocomplete"><input type="text" name="items[${index}][product_name]" placeholder="Select product…" required autocomplete="off"><div class="product-dropdown"></div></div></td>
                                    <td>
                                        <div class="qty-control">
                                            <button type="button" class="qty-btn" onclick="changeQuantity(this, -1)">−</button>
                                            <input type="number" min="1" class="qty-input" name="items[${index}][quantity]" value="1" oninput="updateRowTotal(this)" required>
                                            <button type="button" class="qty-btn" onclick="changeQuantity(this, 1)">+</button>
                                        </div>
                                    </td>
                                    <td><input type="number" step="0.01" min="0" class="price-input" name="items[${index}][unit_price]" value="" placeholder="0.00" inputmode="decimal" oninput="updateRowTotal(this)" required></td>
                                    <td><span class="row-total-wrap">$<span class="row-total">0.00</span></span></td>
                                    <td><button type="button" class="btn btn-danger btn-small" onclick="removeOrderItem(this)">Remove</button></td>
                                `;
                                tbody.appendChild(tr);
                                bindItemRow(tr);
                                initProductAutocomplete(tr.querySelector('.product-autocomplete'));
                                updateOrderTotal();
                            };

                            window.changeQuantity = function(btn, delta){
                                const row = btn.closest('tr');
                                const input = row.querySelector('input[type=number][name*="[quantity]"]');
                                if(!input) return;
                                let val = parseInt(input.value || 0,10);
                                val = Math.max(1, val + delta);
                                input.value = val;
                                updateRowTotal(input);
                            };

                            window.updateRowTotal = function(el){
                                const row = el.closest('tr');
                                if(!row) return;
                                const qtyEl = row.querySelector('input[name*="[quantity]"]');
                                const priceEl = row.querySelector('input[name*="[unit_price]"]');
                                const qty = Number(qtyEl ? qtyEl.value : 0) || 0;
                                const price = Number(priceEl ? priceEl.value : 0) || 0;
                                const total = qty * price;
                                const span = row.querySelector('.row-total'); if(span) span.textContent = toFixed2(total);
                                updateOrderTotal();
                            };

                            window.removeOrderItem = function(btn){
                                const tr = btn.closest('tr'); if(!tr) return;
                                const tbody = tr.parentElement; tr.remove();
                                // reindex names
                                Array.from(tbody.children).forEach((r,i)=>{
                                    r.querySelectorAll('input').forEach(inp=>{
                                        inp.name = inp.name.replace(/items\[\d+\]/, 'items['+i+']');
                                    });
                                });
                                updateOrderTotal();
                            };

                            function updateOrderTotal(){
                                const totals = Array.from(document.querySelectorAll('.row-total')).map(s=>Number(s.textContent||0));
                                const sum = totals.reduce((a,b)=>a+b,0);
                                const el = document.getElementById('order-total-value'); if(el) el.textContent = toFixed2(sum);
                            }

                            window.openOrderModal = function(order){
                                const form = document.getElementById('order-form'); if(!form) return;
                                // refresh product list each time modal opens
                                window._productsFetched = false;
                                fetchProducts();
                                // reset form fields
                                form.reset();
                                const tbody = document.getElementById('order-items'); if(!tbody) return; tbody.innerHTML = '';
                                const orderItems = (order && (order.orderItems || order.order_items)) || [];
                                if(order && orderItems.length){
                                    orderItems.forEach((it,i)=>{
                                        const tr = document.createElement('tr');
                                        const unitPrice = Number(it.unit_price || 0);
                                        tr.innerHTML = `
                                            <td><div class="product-autocomplete"><input type="text" name="items[${i}][product_name]" value="${String(it.product_name||'').replace(/"/g,'&quot;')}" placeholder="Select product…" required autocomplete="off"><div class="product-dropdown"></div></div></td>
                                            <td>
                                                <div class="qty-control">
                                                    <button type="button" class="qty-btn" onclick="changeQuantity(this, -1)">−</button>
                                                    <input type="number" min="1" class="qty-input" name="items[${i}][quantity]" value="${Number(it.quantity||1)}" oninput="updateRowTotal(this)" required>
                                                    <button type="button" class="qty-btn" onclick="changeQuantity(this, 1)">+</button>
                                                </div>
                                            </td>
                                            <td><input type="number" step="0.01" min="0" class="price-input" name="items[${i}][unit_price]" value="${unitPrice > 0 ? unitPrice : ''}" placeholder="0.00" inputmode="decimal" oninput="updateRowTotal(this)" required></td>
                                            <td><span class="row-total-wrap">$<span class="row-total">${toFixed2((Number(it.quantity||1))*(Number(it.unit_price||0)))}</span></span></td>
                                            <td><button type="button" class="btn btn-danger btn-small" onclick="removeOrderItem(this)">Remove</button></td>
                                        `;
                                        tbody.appendChild(tr);
                                        bindItemRow(tr);
                                        initProductAutocomplete(tr.querySelector('.product-autocomplete'));
                                    });
                                    // populate fields
                                    ['customer_name','phone','address','payment_type','payment_status','delivery_type'].forEach(f=>{
                                        const el = form.querySelector('[name="'+f+'"]'); if(el && order[f]!==undefined) el.value = order[f];
                                    });
                                    const method = document.getElementById('order-method'); if(method) method.value = 'PUT';
                                    const action = document.getElementById('order-action'); if(action) action.value = 'update';
                                    // set form action to update URL
                                    try { if(order && order.id) form.action = '/orders/' + order.id; } catch(e){}
                                    // update modal title and save button text
                                    const title = document.getElementById('order-modal-title'); if(title) title.textContent = 'Edit Order';
                                    const saveBtn = document.getElementById('step-save'); if(saveBtn) saveBtn.textContent = 'Update Order';
                                } else {
                                    addOrderItem();
                                    const method = document.getElementById('order-method'); if(method) method.value = 'POST';
                                    const action = document.getElementById('order-action'); if(action) action.value = 'store';
                                    // set default create action
                                    form.action = form.dataset.storeAction || form.action;
                                    const title = document.getElementById('order-modal-title'); if(title) title.textContent = 'Create New Order';
                                    const saveBtn = document.getElementById('step-save'); if(saveBtn) saveBtn.textContent = 'Save Order';
                                }
                                updateOrderTotal();
                                showOrderStep(1);
                                // show modal
                                const modal = document.getElementById('order-modal'); if(modal) modal.classList.remove('hidden');
                                const backdrop = modal ? modal.querySelector('.modal-backdrop') : null; if(backdrop) backdrop.classList.remove('hidden');
                                if (typeof setBodyModalOpen === 'function') setBodyModalOpen(true);
                            };

                            window.closeOrderModal = function(resetForm = false){
                                const modal = document.getElementById('order-modal'); if(!modal) return;
                                modal.classList.add('hidden');
                                const backdrop = modal.querySelector('.modal-backdrop'); if(backdrop) backdrop.classList.add('hidden');
                                if (typeof setBodyModalOpen === 'function') setBodyModalOpen(false);
                                if(resetForm){
                                    const form = document.getElementById('order-form');
                                    if(form){
                                        form.reset();
                                        const tbody = document.getElementById('order-items');
                                        if(tbody){ tbody.innerHTML = ''; addOrderItem(); }
                                        updateOrderTotal();
                                        showOrderStep(1);
                                    }
                                }
                            };

                            window.closeReceiptModal = function(){
                                const modal = document.getElementById('receipt-modal'); if(!modal) return;
                                modal.classList.add('hidden');
                                const backdrop = document.getElementById('receipt-backdrop'); if(backdrop) backdrop.classList.add('hidden');
                                if (typeof setBodyModalOpen === 'function') setBodyModalOpen(false);
                            };

                            window.validateOrderStep1 = function(){
                                const step1 = document.getElementById('order-step-1');
                                if(!step1) return;
                                const fields = step1.querySelectorAll('input, select, textarea');
                                for (const field of fields) {
                                    if (!field.checkValidity()) {
                                        field.reportValidity();
                                        return;
                                    }
                                }
                                showOrderStep(2);
                            };

                            document.addEventListener('DOMContentLoaded', function(){
                                document.querySelectorAll('#order-items tr').forEach(bindItemRow);
                                // Init autocomplete on existing rows
                                document.querySelectorAll('#order-items .product-autocomplete').forEach(initProductAutocomplete);
                                // Pre-fetch products
                                fetchProducts();
                                function normalizePriceInputs(){
                                    document.querySelectorAll('.price-input').forEach(function(input){
                                        if(input.value === '') input.value = '0';
                                    });
                                }
                                const orderForm = document.getElementById('order-form');
                                const saveBtn = document.getElementById('step-save');
                                if(saveBtn){
                                    saveBtn.addEventListener('click', normalizePriceInputs);
                                }
                                if(orderForm){
                                    orderForm.addEventListener('submit', normalizePriceInputs);
                                }
                                updateOrderTotal();
                                showOrderStep(1);
                                const orderReturn = document.getElementById('order-modal-return');
                                const orderAction = document.getElementById('order-action');
                                if(orderReturn){
                                    orderReturn.addEventListener('click', function(){
                                        closeOrderModal(orderAction && orderAction.value === 'store');
                                    });
                                }
                                const receiptReturn = document.getElementById('receipt-modal-return');
                                if(receiptReturn){
                                    receiptReturn.addEventListener('click', closeReceiptModal);
                                }
                            });

                        })();
                        </script>

                <div id="order-step-2" class="form-step">
                    <div class="items-section-header">
                        <div>
                            <h3>Order Items</h3>
                            <p class="text-slate section-lead">Add each perfume item, quantity, and price.</p>
                        </div>
                        <button type="button" class="btn btn-secondary btn-small" onclick="addOrderItem()">Add more</button>
                    </div>

                    <div class="table-scroll">
                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="order-items">
                                @foreach($oldItems as $index => $item)
                                    <tr>
                                        <td><div class="product-autocomplete"><input type="text" name="items[{{ $index }}][product_name]" value="{{ $item['product_name'] }}" placeholder="Select product…" required autocomplete="off"><div class="product-dropdown"></div></div></td>
                                        <td>
                                            <div class="qty-control">
                                                <button type="button" class="qty-btn" onclick="changeQuantity(this, -1)">−</button>
                                                <input type="number" min="1" class="qty-input" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}" oninput="updateRowTotal(this)" required>
                                                <button type="button" class="qty-btn" onclick="changeQuantity(this, 1)">+</button>
                                            </div>
                                        </td>
                                        <td><input type="number" step="0.01" min="0" class="price-input" name="items[{{ $index }}][unit_price]" value="{{ ($item['unit_price'] ?? '') !== '' && ($item['unit_price'] ?? 0) != 0 ? $item['unit_price'] : '' }}" placeholder="0.00" inputmode="decimal" oninput="updateRowTotal(this)" required></td>
                                        <td><span class="row-total-wrap">$<span class="row-total">{{ number_format((float) ($item['quantity'] ?? 1) * (float) ($item['unit_price'] ?: 0), 2) }}</span></span></td>
                                        <td><button type="button" class="btn btn-danger btn-small" onclick="removeOrderItem(this)">Remove</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="order-total-bar">
                        <span>Total price</span>
                        <span>$<span id="order-total-value">0.00</span></span>
                    </div>
                </div>
                </div>

                <div class="modal-actions modal-actions-sticky">
                    <button type="button" class="btn btn-secondary btn-small" id="step-back" onclick="showOrderStep(currentStep - 1)">Back</button>
                    <button type="button" class="btn btn-primary btn-small" id="step-next" onclick="validateOrderStep1()">Next</button>
                    <button type="submit" class="btn btn-primary btn-small hidden" id="step-save">Save Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
