<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $date = $request->query('date');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->get();

        $soldCounts = OrderItem::selectRaw('order_items.product_name, SUM(order_items.quantity) as total_sold')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($date, function ($query, $date) {
                return $query->whereDate('orders.created_at', $date);
            })
            ->groupBy('order_items.product_name')
            ->pluck('total_sold', 'product_name');

        foreach ($products as $product) {
            $product->total_sold = $soldCounts->get($product->name, 0);
        }

        return view('products.index', [
            'products' => $products,
            'search' => $search,
            'date' => $date,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
        ], [
            'name.unique' => 'This product name already exists.',
        ]);

        Product::create($validated);

        return Redirect::route('products.index')->with('success', 'Product added successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
        ], [
            'name.unique' => 'This product name already exists.',
        ]);

        $oldName = $product->name;
        $product->update($validated);

        if ($oldName !== $validated['name']) {
            OrderItem::where('product_name', $oldName)->update(['product_name' => $validated['name']]);
        }

        return Redirect::route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return Redirect::route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * JSON endpoint for autocomplete / dropdown in order modal.
     */
    public function apiList()
    {
        return response()->json(
            Product::orderBy('name')->pluck('name')
        );
    }
}
