@extends('layouts.app')

@section('title', 'Transcent Profumo · Dashboard')

@section('content')
    <div class="dashboard-wrapper">
        <div class="brand-panel">
            <div class="brand-row">
                <div class="brand-chip">T</div>
                <div class="brand-title">
                    <h1>Transcent Profumo</h1>
                    <p>Business Dashboard</p>
                </div>
            </div>
            <div class="action-group">
                <a href="{{ route('orders.index') }}" class="btn btn-ghost">← Back to Orders</a>
                @include('partials.user-menu')
            </div>
        </div>

        {{-- Date Filter Bar --}}
        <div class="page-card dashboard-card" style="padding: 24px 28px;">
            <div class="date-filter-header">
                <div>
                    <h2 class="dash-section-title" style="margin-bottom: 4px;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="vertical-align: -3px; margin-right: 6px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Filter Dashboard
                    </h2>
                    <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Select a date range to view analytics</p>
                </div>
                @if($hasDateFilter)
                    <span class="filter-chip" style="font-size: 0.9rem;">
                        📅 Showing: <strong style="margin-left: 4px;">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</strong>
                    </span>
                @else
                    <span class="filter-chip" style="background: #f0fdf4; color: #166534; border-color: #bbf7d0;">
                        ✓ Showing all-time data
                    </span>
                @endif
            </div>
            <div class="date-filter-body">
                <div class="date-quick-picks">
                    <span style="font-size: 0.82rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Quick:</span>
                    <a href="{{ route('dashboard', ['date_range' => 'today']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'today' ? 'active-pick' : '' }}">Today</a>
                    <a href="{{ route('dashboard', ['date_range' => 'yesterday']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'yesterday' ? 'active-pick' : '' }}">Yesterday</a>
                    <a href="{{ route('dashboard', ['date_range' => 'last_7_days']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'last_7_days' ? 'active-pick' : '' }}">Last 7 Days</a>
                    <a href="{{ route('dashboard', ['date_range' => 'last_30_days']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'last_30_days' ? 'active-pick' : '' }}">Last 30 Days</a>
                    <a href="{{ route('dashboard', ['date_range' => 'this_month']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'this_month' ? 'active-pick' : '' }}">This Month</a>
                </div>
                <form method="GET" action="{{ route('dashboard') }}" class="date-filter-form" style="margin-top: 14px;">
                    <div class="date-filter-left">
                        <select name="date_range" class="search-select" onchange="this.form.submit()" style="flex: 0 0 200px; min-width: 170px;">
                            <option value="all_time" {{ $dateRange === 'all_time' ? 'selected' : '' }}>All Time</option>
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $dateRange === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="last_7_days" {{ $dateRange === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30_days" {{ $dateRange === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_month" {{ $dateRange === 'this_month' ? 'selected' : '' }}>This Month</option>
                        </select>
                        @if($hasDateFilter)
                            <a href="{{ route('dashboard', ['date_range' => 'all_time']) }}" class="btn btn-secondary btn-small">✕ Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon" style="background: #f0fdf4; color: #166534;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">{{ $hasDateFilter ? 'Revenue' : 'Total Revenue' }}</span>
                    <span class="kpi-value">${{ number_format($totalRevenue, 2) }}</span>
                    @if($growthRevenue !== null)
                        <span class="kpi-trend {{ $growthRevenue >= 0 ? 'trend-up' : 'trend-down' }}">
                            {{ $growthRevenue >= 0 ? '↑' : '↓' }} {{ number_format(abs($growthRevenue), 1) }}% vs prev
                        </span>
                    @endif
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background: #eff6ff; color: #1e40af;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">{{ $hasDateFilter ? 'Orders' : 'Total Orders' }}</span>
                    <span class="kpi-value">{{ $totalOrders }}</span>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background: #fef9c3; color: #854d0e;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">Average Order Value</span>
                    <span class="kpi-value">${{ number_format($aov, 2) }}</span>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background: #faf5ff; color: #7c3aed;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">Today's Revenue</span>
                    <span class="kpi-value">${{ number_format($todayRevenue, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Status Overview + Monthly Revenue --}}
        <div class="dash-row">
            {{-- Order Status Chart --}}
            <div class="page-card dashboard-card dash-col-sm">
                <h2 class="dash-section-title">Order Status</h2>
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- Monthly Revenue Chart --}}
            <div class="page-card dashboard-card dash-col-lg">
                <h2 class="dash-section-title">Monthly Revenue</h2>
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="page-card dashboard-card" style="margin-bottom: 16px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                    <div>
                        <h2 class="dash-section-title" style="margin-bottom: 0;">Top Products</h2>
                        <p style="color: #64748b; margin: 6px 0 0; font-size: 0.92rem;">Best selling products by quantity</p>
                    </div>
                    <span class="orders-count">{{ $topProducts->count() }} products</span>
                </div>

                @if($topProducts->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">📦</div>
                        <p>No product data yet</p>
                    </div>
                @else
                    <div class="table-wrapper">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th style="text-align: left; width: 40px;">#</th>
                                    <th style="text-align: left;">Product</th>
                                    <th>Qty</th>
                                    <th style="text-align: right;">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $i => $product)
                                    <tr>
                                        <td style="text-align: left; font-weight: 800; color: #94a3b8;">{{ $i + 1 }}</td>
                                        <td style="text-align: left; font-weight: 700;">{{ $product->product_name }}</td>
                                        <td>
                                            <span class="orders-count">{{ number_format($product->total_qty) }}</span>
                                        </td>
                                        <td style="text-align: right; font-weight: 700; color: #111;">${{ number_format($product->total_revenue, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        {{-- Recent Orders --}}
        <div class="page-card dashboard-card">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                <div>
                    <h2 class="dash-section-title" style="margin-bottom: 0;">Recent Orders</h2>
                    <p style="color: #64748b; margin: 6px 0 0; font-size: 0.92rem;">Latest 5 orders</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-small">View All →</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <p>No orders yet</p>
                </div>
            @else
                <div class="table-wrapper">
                    <table class="orders-table" style="min-width: 600px;">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Order #</th>
                                <th style="text-align: left;">Customer</th>
                                <th>Status</th>
                                <th style="text-align: right;">Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td style="text-align: left; font-weight: 700;">{{ $order->order_number }}</td>
                                    <td style="text-align: left;">{{ $order->customer_name }}</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'Not yet in Progress' => 'color:#9a3412; background:#fff7ed; border:1px solid #fed7aa;',
                                                'In Progress' => 'color:#1e40af; background:#eff6ff; border:1px solid #bfdbfe;',
                                                'Completed' => 'color:#166534; background:#f0fdf4; border:1px solid #bbf7d0;',
                                                'Cancelled' => 'color:#991b1b; background:#fef2f2; border:1px solid #fecaca;',
                                            ];
                                        @endphp
                                        <span class="status-badge" style="{{ $statusClasses[$order->status] ?? '' }}">{{ $order->status }}</span>
                                    </td>
                                    <td style="text-align: right; font-weight: 700;">${{ number_format($order->total_price, 2) }}</td>
                                    <td style="color: #64748b; font-size: 0.9rem;">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Status Doughnut Chart ---
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = {
            labels: ['Not yet in Progress', 'In Progress', 'Completed', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $pendingOrders }},
                    {{ $statusBreakdown['In Progress'] ?? 0 }},
                    {{ $completedOrders }},
                    {{ $cancelledOrders }}
                ],
                backgroundColor: [
                    '#f97316', // Orange
                    '#3b82f6', // Blue
                    '#22c55e', // Green
                    '#ef4444'  // Red
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        };

        new Chart(statusCtx, {
            type: 'doughnut',
            data: statusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                family: 'Inter, sans-serif',
                                size: 13,
                                weight: '600'
                            },
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111',
                        titleFont: { size: 14, family: 'Inter' },
                        bodyFont: { size: 14, family: 'Inter', weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true
                    }
                }
            }
        });

        // --- Monthly Revenue Bar Chart ---
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const months = {!! json_encode(array_column(array_reverse($monthlyData), 'label')) !!};
        const revenues = {!! json_encode(array_column(array_reverse($monthlyData), 'revenue')) !!};
        
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue',
                    data: revenues,
                    backgroundColor: '#111111',
                    borderRadius: 8,
                    barThickness: 32,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#111',
                        titleFont: { size: 14, family: 'Inter' },
                        bodyFont: { size: 14, family: 'Inter', weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f0f0f0',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter, sans-serif', size: 12 },
                            color: '#64748b',
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter, sans-serif', size: 12, weight: '600' },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    .kpi-card {
        background: #fff;
        border-radius: 22px;
        padding: 22px 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        border: 1px solid rgba(0,0,0,0.08);
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        transition: all 0.25s ease;
    }
    .kpi-card:hover {
        box-shadow: 0 8px 28px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .kpi-body {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }
    .kpi-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        white-space: nowrap;
    }
    .kpi-value {
        font-size: 1.55rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #111;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .kpi-trend {
        font-size: 0.78rem;
        font-weight: 700;
        margin-top: 2px;
    }
    .trend-up {
        color: #166534;
    }
    .trend-down {
        color: #dc2626;
    }
    .dash-row {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 16px;
    }
    .dash-row-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        align-items: stretch;
    }
    .dash-row-equal .dashboard-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .dash-row-equal .dashboard-card .table-wrapper {
        flex: 1;
    }
    .dash-section-title {
        margin: 0 0 20px 0;
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .date-filter-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }
    .date-filter-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding-top: 18px;
        border-top: 1px solid #f0f0f0;
    }
    .date-quick-picks {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .date-quick-picks .active-pick {
        background: #111 !important;
        color: #fff !important;
        border-color: #111 !important;
    }
    .date-filter-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 1100px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .dash-row, .dash-row-equal { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .kpi-grid { grid-template-columns: 1fr; }
        .date-filter-header { flex-direction: column; }
        .date-filter-body { flex-direction: column; align-items: stretch; }
        .date-filter-left { flex-direction: column; }
        .date-filter-left input, .date-filter-left select { flex: 1 1 auto !important; width: 100% !important; }
        .date-quick-picks { justify-content: center; }
    }
</style>
@endpush
