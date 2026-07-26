@extends('layouts.app')

@section('title', 'Transcent Profumo · Dashboard')

@section('content')
    <div class="dashboard-wrapper">
        {{-- Luxury Brand Header Panel --}}
        <div class="brand-panel">
            <div class="brand-row">
                @include('partials.user-menu')
                <div class="brand-chip">
                    <img src="{{ asset('431219605_1100922654446504_1462438396502192723_n.jpg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="brand-title">
                    <h1>Transcent Profumo</h1>
                    <p>Business Dashboard</p>
                </div>
            </div>
            <div class="action-group">
                <a href="{{ route('orders.index') }}" class="btn btn-ghost">← Back to Orders</a>
            </div>
        </div>

        {{-- Date Filter Bar --}}
        <div class="page-card dashboard-card" style="padding: 24px 28px; border: 1px solid var(--border);">
            <div class="date-filter-header">
                <div>
                    <h2 class="dash-section-title" style="margin-bottom: 4px; display: flex; align-items: center; gap: 8px; font-family: var(--font); font-weight: 700; font-size: 1.3rem;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="color: var(--accent-gold);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Filter Analytics
                    </h2>
                    <p style="color: #64748b; margin: 0; font-size: 0.88rem;">Select a date range to load store metrics</p>
                </div>
                @if($hasDateFilter)
                    <span class="filter-chip" style="font-size: 0.88rem; background: var(--accent-gold-light); color: var(--accent-gold); border-color: var(--border-strong);">
                        📅 Range: <strong>{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</strong>
                    </span>
                @else
                    <span class="filter-chip" style="background: var(--color-completed-bg); color: var(--color-completed-text); border-color: var(--color-completed-border);">
                        ✓ Showing all-time metrics
                    </span>
                @endif
            </div>
            <div class="date-filter-body">
                <div class="date-quick-picks">
                    <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-right: 6px;">Quick Range:</span>
                    <a href="{{ route('dashboard', ['date_range' => 'today']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'today' ? 'active-pick' : '' }}">Today</a>
                    <a href="{{ route('dashboard', ['date_range' => 'yesterday']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'yesterday' ? 'active-pick' : '' }}">Yesterday</a>
                    <a href="{{ route('dashboard', ['date_range' => 'last_7_days']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'last_7_days' ? 'active-pick' : '' }}">Last 7 Days</a>
                    <a href="{{ route('dashboard', ['date_range' => 'last_30_days']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'last_30_days' ? 'active-pick' : '' }}">Last 30 Days</a>
                    <a href="{{ route('dashboard', ['date_range' => 'this_month']) }}" class="btn btn-secondary btn-small {{ $dateRange === 'this_month' ? 'active-pick' : '' }}">This Month</a>
                </div>
                <form method="GET" action="{{ route('dashboard') }}" class="date-filter-form" style="margin: 0;">
                    <div class="date-filter-left">
                        <select name="date_range" class="search-select" onchange="this.form.submit()" style="flex: 0 0 200px; min-width: 170px; height: 38px; min-height: 38px; padding: 6px 14px; border-radius: 8px;">
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

        {{-- KPI Cards Grid --}}
        <div class="kpi-grid">
            {{-- Revenue Card --}}
            <div class="kpi-card">
                <div class="kpi-icon" style="background: var(--accent-gold-light); color: var(--accent-gold);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">{{ $hasDateFilter ? 'Revenue' : 'Total Revenue' }}</span>
                    <span class="kpi-value">${{ number_format($totalRevenue, 2) }}</span>
                    @if($growthRevenue !== null)
                        <span class="kpi-trend {{ $growthRevenue >= 0 ? 'trend-up' : 'trend-down' }}">
                            @if($growthRevenue >= 0)
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align: -1px;"><path d="M18 15l-6-6-6 6"/></svg>
                            @else
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align: -1px;"><path d="M6 9l6 6 6-6"/></svg>
                            @endif
                            {{ number_format(abs($growthRevenue), 1) }}% vs prev
                        </span>
                    @endif
                </div>
            </div>

            {{-- Orders Card --}}
            <div class="kpi-card">
                <div class="kpi-icon" style="background: rgba(20,20,20,0.03); color: var(--primary);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">{{ $hasDateFilter ? 'Orders' : 'Total Orders' }}</span>
                    <span class="kpi-value">{{ $totalOrders }}</span>
                </div>
            </div>

            {{-- AOV Card --}}
            <div class="kpi-card">
                <div class="kpi-icon" style="background: var(--accent-gold-light); color: var(--accent-gold);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v8M9 12h6"/>
                    </svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">Average Order Value</span>
                    <span class="kpi-value">${{ number_format($aov, 2) }}</span>
                </div>
            </div>

            {{-- Today's Revenue Card --}}
            <div class="kpi-card">
                <div class="kpi-icon" style="background: rgba(20,20,20,0.03); color: var(--primary);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
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
            <div class="page-card dashboard-card dash-col-sm" style="border: 1px solid var(--border); overflow: hidden;">
                <div style="margin-bottom: 20px;">
                    <h2 class="dash-section-title" style="font-family: var(--font); font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em;">Status Breakdown</h2>
                    <p style="color: #64748b; margin: 4px 0 0; font-size: 0.85rem;">Acquisitions categorized by order status</p>
                </div>
                <div class="chart-container" style="position: relative; height: 260px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- Monthly Revenue Chart --}}
            <div class="page-card dashboard-card dash-col-lg" style="border: 1px solid var(--border); overflow: hidden;">
                <div style="margin-bottom: 20px;">
                    <h2 class="dash-section-title" style="font-family: var(--font); font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em;">Monthly Sales</h2>
                    <p style="color: #64748b; margin: 4px 0 0; font-size: 0.85rem;">Revenue performance over the last 6 months</p>
                </div>
                <div class="chart-container" style="position: relative; height: 260px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Best Selling Products Column --}}
        <div class="page-card dashboard-card" style="border: 1px solid var(--border); margin-bottom: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                <div>
                    <h2 class="dash-section-title" style="font-family: var(--font); font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em;">Best Selling Products</h2>
                    <p style="color: #64748b; margin: 4px 0 0; font-size: 0.85rem;">Top performing fragrances by quantity sold</p>
                </div>
                <span class="orders-count" style="font-size: 0.78rem; border-color: var(--border-strong); color: var(--accent-gold); background: var(--accent-gold-light);">{{ $topProducts->count() }} items</span>
            </div>

            @if($topProducts->isEmpty())
                <div class="empty-state" style="padding: 32px 16px;">
                    <div class="empty-state-icon">📦</div>
                    <p>No product data yet</p>
                </div>
            @else
                <div class="table-wrapper" style="margin-top: 10px;">
                    <table class="orders-table" style="min-width: 100%; border: none; box-shadow: none;">
                        <thead>
                            <tr>
                                <th style="text-align: left; width: 40px; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 12px 8px; font-size: 0.72rem; letter-spacing: 0.05em; color: #8c8c8c;">#</th>
                                <th style="text-align: left; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 12px 8px; font-size: 0.72rem; letter-spacing: 0.05em; color: #8c8c8c;">Product</th>
                                <th style="text-align: center; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 12px 8px; font-size: 0.72rem; letter-spacing: 0.05em; color: #8c8c8c;">Qty Sold</th>
                                <th style="text-align: right; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 12px 8px; font-size: 0.72rem; letter-spacing: 0.05em; color: #8c8c8c;">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts->take(5) as $i => $product)
                                <tr>
                                    <td style="text-align: left; font-weight: 600; color: var(--accent-gold); padding: 12px 8px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">{{ $i + 1 }}</td>
                                    <td style="text-align: left; font-weight: 600; color: var(--text); padding: 12px 8px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">{{ $product->product_name }}</td>
                                    <td style="text-align: center; padding: 12px 8px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">
                                        <span class="orders-count" style="font-size: 0.8rem; background: var(--surface-muted); color: var(--primary);">{{ number_format($product->total_qty) }}</span>
                                    </td>
                                    <td style="text-align: right; font-weight: 700; color: var(--text); padding: 12px 8px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">${{ number_format($product->total_revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Recent Acquisitions --}}
        <div class="page-card dashboard-card" style="border: 1px solid var(--border);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                <div>
                    <h2 class="dash-section-title" style="font-family: var(--font); font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em;">Recent Acquisitions</h2>
                    <p style="color: #64748b; margin: 4px 0 0; font-size: 0.85rem;">Latest business order transactions</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-small">View All Orders →</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <p>No orders yet</p>
                </div>
            @else
                <div class="table-wrapper" style="margin-top: 10px;">
                    <table class="orders-table" style="min-width: 100%; border: none; box-shadow: none;">
                        <thead>
                            <tr>
                                <th style="text-align: left; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 16px 12px; font-size: 0.75rem; color: #8c8c8c; letter-spacing: 0.05em;">Order #</th>
                                <th style="text-align: left; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 16px 12px; font-size: 0.75rem; color: #8c8c8c; letter-spacing: 0.05em;">Customer</th>
                                <th style="text-align: center; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 16px 12px; font-size: 0.75rem; color: #8c8c8c; letter-spacing: 0.05em;">Status</th>
                                <th style="text-align: right; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 16px 12px; font-size: 0.75rem; color: #8c8c8c; letter-spacing: 0.05em;">Total</th>
                                <th style="text-align: right; background: transparent; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 16px 12px; font-size: 0.75rem; color: #8c8c8c; letter-spacing: 0.05em;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td style="text-align: left; font-weight: 700; color: var(--primary); padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">{{ $order->order_number }}</td>
                                    <td style="text-align: left; padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">
                                        <div>
                                            <span style="font-weight: 600; color: var(--text); display: block;">{{ $order->customer_name }}</span>
                                            @if($order->phone)
                                                <span style="font-size: 0.75rem; color: #64748b;">{{ $order->phone }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="text-align: center; padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">
                                        <span class="status-badge status-badge-{{ strtolower(str_replace(' ', '-', $order->status)) }}">{{ $order->status }}</span>
                                    </td>
                                    <td style="text-align: right; font-weight: 700; color: var(--text); padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">${{ number_format($order->total_price, 2) }}</td>
                                    <td style="text-align: right; color: #64748b; font-size: 0.85rem; padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.04); background: transparent;">
                                        {{ $order->created_at->format('M d, Y') }}
                                        <span style="display: block; font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">{{ $order->created_at->format('h:i A') }}</span>
                                    </td>
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
        // Set chart default font family
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const labelColor = isDark ? '#9aa0b0' : '#64748b';
        const gridColor = isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0, 0, 0, 0.03)';
        const borderBg = isDark ? '#1a1d27' : '#ffffff';

        // --- Status Doughnut Chart ---
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = {
            labels: ['In Progress', 'Completed'],
            datasets: [{
                data: [
                    {{ $inProgressOrders }},
                    {{ $completedOrders }}
                ],
                backgroundColor: [
                    '#1e40af', // Blue (In Progress)
                    '#166534'  // Green (Completed)
                ],
                borderWidth: 2,
                borderColor: borderBg,
                hoverOffset: 6
            }]
        };

        new Chart(statusCtx, {
            type: 'doughnut',
            data: statusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            color: labelColor,
                            usePointStyle: true,
                            padding: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#242733' : '#141414',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 13 },
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
        
        // Gradient fill for bars
        const barGradient = revenueCtx.createLinearGradient(0, 0, 0, 260);
        if (isDark) {
            barGradient.addColorStop(0, '#60a5fa'); // Vibrant electric sky-blue top
            barGradient.addColorStop(1, '#1d4ed8'); // Deep royal blue base
        } else {
            barGradient.addColorStop(0, '#3b82f6'); 
            barGradient.addColorStop(1, '#1e40af');
        }
        
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue',
                    data: revenues,
                    backgroundColor: barGradient,
                    hoverBackgroundColor: isDark ? '#93c5fd' : '#2563eb',
                    borderRadius: 8,
                    barThickness: 26,
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
                        backgroundColor: '#141414',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ' $' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11 },
                            color: labelColor,
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11, weight: '600' },
                            color: labelColor
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
        gap: 20px;
    }
    .kpi-card {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-card);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .kpi-card:hover {
        box-shadow: var(--shadow-premium);
        transform: translateY(-3px);
        border-color: rgba(184, 144, 71, 0.3);
    }
    .kpi-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .kpi-card:hover .kpi-icon {
        transform: scale(1.05);
    }
    .kpi-body {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }
    .kpi-label {
        font-size: 0.8rem;
        color: #8c8c8c;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    .kpi-value {
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .kpi-trend {
        font-size: 0.78rem;
        font-weight: 700;
        margin-top: 2px;
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }
    .trend-up {
        color: #b89047;
    }
    .trend-down {
        color: #c2410c;
    }
    .dash-row {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
    }
    .dash-row-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--primary);
    }
    .date-filter-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .date-filter-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    .date-quick-picks {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .date-quick-picks .btn-secondary {
        border-radius: 6px;
        padding: 0 12px;
        min-height: 34px;
        font-size: 0.8rem;
    }
    .date-quick-picks .active-pick {
        background: var(--primary) !important;
        color: #fff !important;
        border-color: var(--primary) !important;
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

    /* ===== Dashboard Dark Mode ===== */
    html[data-theme="dark"] .kpi-card { background: #1a1d27; }
    html[data-theme="dark"] .kpi-card:hover { border-color: rgba(148, 163, 184, 0.3); }
    html[data-theme="dark"] .kpi-label { color: #9aa0b0; }
    html[data-theme="dark"] .trend-up { color: #60a5fa; }
    html[data-theme="dark"] .date-filter-body { border-top-color: rgba(255,255,255,0.06); }
</style>
@endpush
