<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->query('date_range', 'all_time');
        
        $startDate = null;
        $endDate = null;
        $prevStartDate = null;
        $prevEndDate = null;

        $now = Carbon::now();
        $today = Carbon::today();

        switch ($dateRange) {
            case 'today':
                $startDate = $today->copy();
                $endDate = $today->copy()->endOfDay();
                $prevStartDate = $today->copy()->subDay();
                $prevEndDate = $today->copy()->subDay()->endOfDay();
                break;
            case 'yesterday':
                $startDate = $today->copy()->subDay();
                $endDate = $today->copy()->subDay()->endOfDay();
                $prevStartDate = $today->copy()->subDays(2);
                $prevEndDate = $today->copy()->subDays(2)->endOfDay();
                break;
            case 'last_7_days':
                $startDate = $today->copy()->subDays(6);
                $endDate = $today->copy()->endOfDay();
                $prevStartDate = $today->copy()->subDays(13);
                $prevEndDate = $today->copy()->subDays(7)->endOfDay();
                break;
            case 'last_30_days':
                $startDate = $today->copy()->subDays(29);
                $endDate = $today->copy()->endOfDay();
                $prevStartDate = $today->copy()->subDays(59);
                $prevEndDate = $today->copy()->subDays(30)->endOfDay();
                break;
            case 'this_month':
                $startDate = $today->copy()->startOfMonth();
                $endDate = $today->copy()->endOfDay();
                $prevStartDate = $today->copy()->subMonth()->startOfMonth();
                $prevEndDate = $today->copy()->subMonth()->endOfMonth();
                break;
            case 'all_time':
            default:
                $startDate = null;
                $endDate = null;
                break;
        }

        $hasDateFilter = $dateRange !== 'all_time';

        // Build a base query scoped to the date if provided
        $orderQuery = Order::query();
        if ($hasDateFilter && $startDate && $endDate) {
            $orderQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Overall stats (scoped to date filter)
        $totalRevenue = (clone $orderQuery)->where('status', '!=', 'Cancelled')->sum('total_price');
        $totalOrders = (clone $orderQuery)->count();
        $completedOrders = (clone $orderQuery)->where('status', 'Completed')->count();
        $inProgressOrders = (clone $orderQuery)->where('status', 'In Progress')->count();
        $pendingOrders = 0;
        $cancelledOrders = 0;

        // Calculate AOV
        $aov = $totalOrders > 0 ? $totalRevenue / (clone $orderQuery)->where('status', '!=', 'Cancelled')->count() : 0;
        if (is_nan($aov) || is_infinite($aov)) $aov = 0; // fallback if only cancelled orders

        // Calculate Growth if not all time
        $growthRevenue = null;
        if ($hasDateFilter && $prevStartDate && $prevEndDate) {
            $prevRevenue = Order::where('status', '!=', 'Cancelled')
                ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
                ->sum('total_price');
            
            if ($prevRevenue > 0) {
                $growthRevenue = (($totalRevenue - $prevRevenue) / $prevRevenue) * 100;
            } else if ($totalRevenue > 0) {
                $growthRevenue = 100; // 100% growth if prev was 0 and now we have > 0
            } else {
                $growthRevenue = 0;
            }
        }

        // Today's stats
        $todayRevenue = Order::where('status', '!=', 'Cancelled')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();

        // This month stats
        $monthRevenue = Order::where('status', '!=', 'Cancelled')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        // Top products by quantity sold (scoped to date filter)
        $topProductsQuery = OrderItem::select(
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereHas('order', function ($q) use ($hasDateFilter, $startDate, $endDate) {
                $q->where('status', '!=', 'Cancelled');
                if ($hasDateFilter && $startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(10);

        $topProducts = $topProductsQuery->get();

        // Top Customers by Revenue (scoped to date filter)
        $topCustomersQuery = Order::select(
                'customer_name',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_price) as total_spent')
            )
            ->where('status', '!=', 'Cancelled');
            
        if ($hasDateFilter && $startDate && $endDate) {
            $topCustomersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $topCustomers = $topCustomersQuery->groupBy('customer_name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        // Recent orders (scoped to date filter)
        $recentOrdersQuery = Order::with('orderItems');
        if ($hasDateFilter && $startDate && $endDate) {
            $recentOrdersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $recentOrders = $recentOrdersQuery
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Monthly revenue for the last 6 months (always show full picture)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $revenue = Order::where('status', '!=', 'Cancelled')
                ->whereMonth('created_at', $monthDate->month)
                ->whereYear('created_at', $monthDate->year)
                ->sum('total_price');
            $count = Order::whereMonth('created_at', $monthDate->month)
                ->whereYear('created_at', $monthDate->year)
                ->count();
            $monthlyData[] = [
                'label' => $monthDate->format('M Y'),
                'revenue' => (float) $revenue,
                'orders' => $count,
            ];
        }

        // Order status breakdown (scoped to date filter)
        $statusQuery = clone $orderQuery;
        $statusBreakdown = $statusQuery->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard.index', compact(
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'inProgressOrders',
            'pendingOrders',
            'cancelledOrders',
            'todayRevenue',
            'todayOrders',
            'monthRevenue',
            'topProducts',
            'topCustomers',
            'recentOrders',
            'monthlyData',
            'statusBreakdown',
            'dateRange',
            'hasDateFilter',
            'startDate',
            'endDate',
            'aov',
            'growthRevenue'
        ));
    }
}
