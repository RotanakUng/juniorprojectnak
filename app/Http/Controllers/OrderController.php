<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    protected function statuses(): array
    {
        return [
            'In Progress',
            'Completed',
        ];
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $tab = $request->query('tab', 'orders');
        $date = $request->query('date');

        $orders = Order::with('orderItems')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('order_number', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($date, function ($query, $date) {
                return $query->whereDate('created_at', $date);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
            'date' => $date,
            'tab' => $tab,
            'statuses' => $this->statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'payment_type' => ['required', 'string', 'max:50'],
            'payment_status' => ['required', Rule::in(['Unpaid', 'Paid'])],
            'delivery_type' => ['required', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ], [
            'items.required' => 'Please add at least one order item.',
            'items.*.product_name.required' => 'Please enter an item name for every order item.',
            'items.*.quantity.min' => 'Quantity must be at least 1 for every order item.',
            'items.*.unit_price.min' => 'Price must be at least 0 for every order item.',
        ]);

        DB::transaction(function () use ($validated, $request, &$order) {
            $order = Order::create([
                'order_number' => 'temp_' . uniqid(),
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'payment_type' => $validated['payment_type'],
                'payment_status' => $validated['payment_status'],
                'delivery_type' => $validated['delivery_type'],
                'status' => 'In Progress',
                'total_price' => 0,
            ]);

            $order->order_number = 'ord' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            $order->save();


            foreach ($validated['items'] as $item) {
                $order->orderItems()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            $order->refresh()->recalculateTotal()->save();
        });

        return Redirect::route('orders.index')->with('success', 'Order created successfully.');
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'payment_type' => ['required', 'string', 'max:50'],
            'payment_status' => ['required', Rule::in(['Unpaid', 'Paid'])],
            'delivery_type' => ['required', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ], [
            'items.required' => 'Please add at least one order item.',
            'items.*.product_name.required' => 'Please enter an item name for every order item.',
            'items.*.quantity.min' => 'Quantity must be at least 1 for every order item.',
            'items.*.unit_price.min' => 'Price must be at least 0 for every order item.',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'payment_type' => $validated['payment_type'],
                'payment_status' => $validated['payment_status'],
                'delivery_type' => $validated['delivery_type'],
            ]);

            $order->orderItems()->delete();

            foreach ($validated['items'] as $item) {
                $order->orderItems()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            $order->refresh()->recalculateTotal()->save();
        });

        return Redirect::route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in($this->statuses())],
        ]);

        $order->update(['status' => $validated['status']]);

        return Redirect::route('orders.index')->with('success', 'Order status updated.');
    }

    public function show(Request $request, Order $order)
    {
        return response()->json($order->load('orderItems'));
    }

    public function destroy(Order $order)
    {
        $order->orderItems()->delete();
        $order->delete();

        return Redirect::route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function exportCsv(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $date = $request->query('date');

        $orders = Order::with('orderItems')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('order_number', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($date, function ($query, $date) {
                return $query->whereDate('created_at', $date);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'orders-' . now()->format('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order ID',
                'Order Date',
                'Status',
                'Customer',
                'Phone',
                'Address',
                'Payment',
                'Payment Status',
                'Delivery',
                'Shipping Zone',
                'Items',
                'Order Total',
            ]);

            foreach ($orders as $order) {
                $items = $order->orderItems->map(function ($item) {
                    return sprintf('%s - %dx $%.2f = $%.2f', $item->product_name, $item->quantity, $item->unit_price, $item->total_price);
                })->implode('; ');

                fputcsv($handle, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->status,
                    $order->customer_name,
                    $order->phone,
                    $order->address,
                    $order->payment_type,
                    $order->payment_status,
                    $order->delivery_type,
                    $order->shipping_zone,
                    $items,
                    sprintf('$%.2f', $order->total_price),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadPdf(Order $order)
    {
        $pdf = Pdf::loadView('orders.pdf', [
            'order' => $order->load('orderItems'),
        ]);

        return $pdf->download("receipt-{$order->order_number}.pdf");
    }

    public function print(Order $order)
    {
        if ($order->status === 'In Progress') {
            $order->update(['status' => 'Completed']);
        }

        return view('orders.print', [
            'order' => $order->load('orderItems'),
        ]);
    }

    public function printBulk(Request $request)
    {
        $idsRaw = $request->query('ids', '');
        $ids = array_filter(array_map('intval', explode(',', $idsRaw)));

        $orders = Order::with('orderItems')
            ->whereIn('id', $ids)
            ->get();

        if ($orders->isEmpty()) {
            return Redirect::route('orders.index')->with('error', 'No orders selected for printing.');
        }

        foreach ($orders as $order) {
            if ($order->status === 'In Progress') {
                $order->update(['status' => 'Completed']);
            }
        }

        return view('orders.print-bulk', [
            'orders' => $orders,
        ]);
    }

    public function apiLatest(Request $request)
    {
        $sinceId = (int) $request->query('since_id', 0);
        $lastSync = $request->query('last_sync');

        $newOrders = Order::with('orderItems')
            ->where('id', '>', $sinceId)
            ->orderBy('id', 'asc')
            ->get();

        $maxId = $newOrders->max('id') ?? $sinceId;

        $updatedQuery = Order::query();
        if ($lastSync) {
            try {
                $updatedQuery->where('updated_at', '>=', \Carbon\Carbon::parse($lastSync));
            } catch (\Exception $e) {
                $updatedQuery->where('updated_at', '>=', now()->subSeconds(15));
            }
        } else {
            $updatedQuery->where('updated_at', '>=', now()->subSeconds(15));
        }

        $updatedOrders = $updatedQuery->where('id', '<=', $sinceId)->get(['id', 'status']);
        $activeIds = Order::pluck('id')->toArray();

        return response()->json([
            'count' => $newOrders->count(),
            'max_id' => $maxId,
            'orders' => $newOrders,
            'updated_statuses' => $updatedOrders,
            'active_ids' => $activeIds,
            'server_time' => now()->toIso8601String(),
        ]);
    }
}
