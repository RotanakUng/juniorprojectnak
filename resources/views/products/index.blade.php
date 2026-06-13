@extends('layouts.app')

@section('title', 'Transcent Profumo · Products')

@section('content')
    <div class="dashboard-wrapper">
        <div class="brand-panel">
            <div class="brand-row">
                <div class="brand-chip">T</div>
                <div class="brand-title">
                    <h1>Transcent Profumo</h1>
                    <p>Premium POS · Product Management</p>
                </div>
            </div>
            <div class="action-group">
                <a href="{{ route('orders.index') }}" class="btn btn-ghost">← Back to Orders</a>
                <button id="openAddProductModal" type="button" class="btn btn-primary">Add New Product</button>
                @include('partials.user-menu')
            </div>
        </div>

        <div class="page-card dashboard-card">
            <div class="card-panel">
                <div class="panel-row toolbar-row">
                    <div class="toolbar-filters">
                        <form action="{{ route('products.index') }}" method="GET" class="search-form" id="product-search-form">
                            <input type="text" name="search" class="search-input" placeholder="Search products…" value="{{ $search }}">
                            <input type="date" name="date" class="search-select" value="{{ $date ?? '' }}" style="flex: 0 0 180px; cursor: pointer;" onclick="this.showPicker()" onkeydown="if(event.key!=='Tab')event.preventDefault()">
                        </form>
                    </div>
                    @if($search || ($date ?? false))
                        <div class="filter-chip">
                            Filtered
                            @if($date ?? false)<span>· {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>@endif
                            @if($search)<span>· "{{ $search }}"</span>@endif
                            <a href="{{ route('products.index') }}">Clear</a>
                        </div>
                    @endif
                </div>

                <div class="panel-row section-header">
                    <div>
                        <h2 class="text-lg" style="margin:0;">Products</h2>
                        <p class="text-slate section-lead">Manage your product catalog. These products appear in the order form dropdown.</p>
                    </div>
                    <span class="orders-count">{{ $products->count() }} product{{ $products->count() === 1 ? '' : 's' }}</span>
                </div>

                @if($products->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon" aria-hidden="true">📦</div>
                        <p>No products found.</p>
                        @if($search)
                            <p class="text-slate">Try adjusting your search.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-small">Clear filters</a>
                        @else
                            <p class="text-slate">Add your first product to get started.</p>
                            <button type="button" class="btn btn-primary btn-small" id="empty-add-product">Add New Product</button>
                        @endif
                    </div>
                @else
                    <div class="table-wrapper">
                        <table class="table orders-table products-table">
                            <thead>
                                <tr>
                                    <th style="width:60px;">#</th>
                                    <th>Product Name</th>
                                    <th style="width:140px;">Items Sold</th>
                                    <th style="width:180px;">Date Added</th>
                                    <th style="width:200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="status-badge status-badge-completed" style="background:#f1f5f9; color:#475569; border-color:#cbd5e1;">{{ $product->total_sold }}</span>
                                        </td>
                                        <td>{{ $product->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="row-actions" style="display:flex; gap:8px;">
                                                <button type="button" class="btn btn-secondary btn-small edit-product-btn"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}">Edit</button>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;" class="delete-product-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-small delete-product-btn">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add / Edit Product Modal --}}
    <div id="product-modal" class="hidden no-print">
        <div class="modal-backdrop" id="product-modal-backdrop"></div>
        <div class="modal-container">
            <div class="modal-card" style="width: min(520px, 92vw);">
                <div class="modal-header">
                    <button type="button" class="modal-return-btn" id="product-modal-return">← Return</button>
                    <span class="modal-label" id="product-modal-title">Add New Product</span>
                </div>

                <div class="modal-heading">
                    <h2 id="product-modal-subtitle">Enter the product name.</h2>
                </div>

                <form id="product-form" action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="product-method" />

                    <div class="form-group" style="margin-bottom: 22px;">
                        <label for="product_name_input">Product Name</label>
                        <input type="text" id="product_name_input" name="name" value="" placeholder="e.g. Bleu de Chanel 100ml" required autofocus>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" id="product-modal-cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="product-save-btn">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirm Modal --}}
    <div id="product-delete-overlay" class="overlay hidden no-print">
        <div class="modal-confirm" role="dialog" aria-labelledby="product-delete-title" aria-modal="true">
            <h3 id="product-delete-title">Delete this product?</h3>
            <p class="text-slate section-lead">This action cannot be undone. Existing orders with this product won't be affected.</p>
            <div class="modal-actions">
                <button type="button" id="product-delete-cancel" class="btn btn-secondary">Cancel</button>
                <button type="button" id="product-delete-confirm" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('product-modal');
    const form = document.getElementById('product-form');
    const methodInput = document.getElementById('product-method');
    const nameInput = document.getElementById('product_name_input');
    const titleEl = document.getElementById('product-modal-title');
    const subtitleEl = document.getElementById('product-modal-subtitle');
    const saveBtn = document.getElementById('product-save-btn');
    const storeUrl = '{{ route("products.store") }}';

    function openProductModal(product) {
        form.reset();
        if (product) {
            titleEl.textContent = 'Edit Product';
            subtitleEl.textContent = 'Update the product name.';
            saveBtn.textContent = 'Update Product';
            nameInput.value = product.name;
            methodInput.value = 'PUT';
            form.action = '/products/' + product.id;
        } else {
            titleEl.textContent = 'Add New Product';
            subtitleEl.textContent = 'Enter the product name.';
            saveBtn.textContent = 'Add Product';
            nameInput.value = '';
            methodInput.value = 'POST';
            form.action = storeUrl;
        }
        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
        setTimeout(() => nameInput.focus(), 100);
    }

    function closeProductModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }

    // Open buttons
    const addBtn = document.getElementById('openAddProductModal');
    if (addBtn) addBtn.addEventListener('click', () => openProductModal(null));

    const emptyBtn = document.getElementById('empty-add-product');
    if (emptyBtn) emptyBtn.addEventListener('click', () => openProductModal(null));

    // Close buttons
    document.getElementById('product-modal-return').addEventListener('click', closeProductModal);
    document.getElementById('product-modal-cancel').addEventListener('click', closeProductModal);
    document.getElementById('product-modal-backdrop').addEventListener('click', closeProductModal);

    // Edit buttons
    document.querySelectorAll('.edit-product-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            openProductModal({
                id: this.dataset.id,
                name: this.dataset.name,
            });
        });
    });

    // Delete flow
    let pendingDeleteForm = null;
    document.querySelectorAll('.delete-product-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            pendingDeleteForm = this.closest('form');
            document.getElementById('product-delete-overlay').classList.remove('hidden');
        });
    });

    document.getElementById('product-delete-cancel').addEventListener('click', function () {
        document.getElementById('product-delete-overlay').classList.add('hidden');
        pendingDeleteForm = null;
    });

    document.getElementById('product-delete-confirm').addEventListener('click', function () {
        if (pendingDeleteForm) pendingDeleteForm.submit();
    });

    document.getElementById('product-delete-overlay').addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.add('hidden');
            pendingDeleteForm = null;
        }
    });

    // Search on Enter
    const searchForm = document.getElementById('product-search-form');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });
        }
        const dateInput = searchForm.querySelector('input[name="date"]');
        if (dateInput) {
            dateInput.addEventListener('change', function () {
                searchForm.submit();
            });
        }
    }

    // Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        const deleteOverlay = document.getElementById('product-delete-overlay');
        if (deleteOverlay && !deleteOverlay.classList.contains('hidden')) {
            deleteOverlay.classList.add('hidden');
            pendingDeleteForm = null;
            return;
        }
        if (modal && !modal.classList.contains('hidden')) {
            closeProductModal();
        }
    });
});
</script>
@endpush
