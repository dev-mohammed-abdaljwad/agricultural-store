<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Services\ToastService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display cart items
     */
    public function index()
    {
        $cartItems = auth()->user()->cartItems()
            ->with('product.images')
            ->get()
            ->map(function ($item) {
                return [
                    'cart_item_id' => $item->id,
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'category' => $item->product->category->name ?? '',
                    'supplier' => $item->product->supplier_name,
                    'image' => $item->product->images->first()?->asset_url,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->base_price,
                    'total_price' => $item->quantity * $item->product->base_price,
                ];
            })
            ->toArray();

        $totalWeight = auth()->user()->cartItems()
            ->with('product')
            ->get()
            ->sum(fn($item) => $item->quantity * ($item->product->weight ?? 1));

        $subtotal = collect($cartItems)->sum('total_price');

        return view('customer.orders.cart-review', [
            'cartItems' => $cartItems,
            'itemCount' => count($cartItems),
            'totalWeight' => $totalWeight,
            'subtotal' => $subtotal,
        ]);
    }

    /**
     * Add product to cart after success gose to /cart route
     */
    public function add(Request $request, Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10000',
        ]);
     
        $user = auth()->user();

        // Check if product already in cart
        $cartItem = $user->cartItems()
            ->where('product_id', $product->id)
            ->first();
    
        if ($cartItem) {
            // Update quantity
            $cartItem->update([
                'quantity' => $cartItem->quantity + $validated['quantity'],
            ]);
            ToastService::updated('السلة');
        } else {
            // Create new cart item
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
            ToastService::created('المنتج');
        }

        return redirect()->route('cart.index');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10000',
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية',
                'total' => $cartItem->quantity * $cartItem->product->base_price,
            ]);
        }

        ToastService::updated('الكمية');
        return back();
    }

    /**
     * Remove item from cart
     */
    public function remove(Cart $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        ToastService::deleted('المنتج');
        return redirect()->route('cart.index');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        auth()->user()->cartItems()->delete();

        ToastService::deleted('السلة');
        return back();
    }

    /**
     * Get cart count (for AJAX)
     */
    public function count()
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }

        $count = auth()->user()->cartItems()->count();

        return response()->json(['count' => $count]);
    }
}
