<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    /**
     * List all quotes for the authenticated customer
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $quotes = Order::where('customer_id', Auth::id())
            ->whereHas('quotes')
            ->with(['quotes', 'customer'])
            ->latest()
            ->paginate(15);

        return view('quotes.index', compact('quotes'));
    }

    /**
     * Create a new quote request (place order for quote)
     */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->status !== 'active') {
            return back()->with('error', 'هذا المنتج غير متاح حالياً');
        }

        // Create an order in quote_pending status
        $order = Order::create([
            'order_number' => 'NH-' . now()->year . '-' . Str::random(6),
            'customer_id' => Auth::id(),
            'status' => 'quote_pending',
            'delivery_governorate' => Auth::user()->governorate,
            'delivery_address' => Auth::user()->address,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);

        // Add product as order item
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $product->min_order_qty,
            'unit_price' => null, // Will be set in quote
            'total_price' => null,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'تم إنشاء طلب عرض السعر بنجاح. انتظر رد من الإدارة.');
    }
}
