<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

class VendorDashboardController extends Controller
{
    /**
     * Show the vendor dashboard
     */
    public function index()
    {
        $vendor = Auth::user();
        
        // Only vendors can access this
        if (!$vendor || ($vendor->role !== 'vendor' && $vendor->customer_type !== 'trader')) {
            abort(403, 'غير مصرح بالوصول إلى هذه الصفحة');
        }

        // Get vendor statistics - Orders placed by this vendor user
        $totalSales = Order::where('customer_id', $vendor->id)
            ->sum('total_amount') ?? 0;

        // Products offered by system (all active products)
        $activeProducts = Product::where('status', 'active')
            ->count() ?? 0;

        // Orders from this vendor that are pending or awaiting quote
        $pendingOrders = Order::where('customer_id', $vendor->id)
            ->whereIn('status', ['pending', 'quote_pending'])
            ->count() ?? 0;

        // Success rate based on completed orders
        $totalOrders = Order::where('customer_id', $vendor->id)->count() ?? 1;
        $successfulOrders = Order::where('customer_id', $vendor->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->count() ?? 0;
        $successRate = $totalOrders > 0 ? round(($successfulOrders / $totalOrders) * 100) : 0;

        // Get recent orders from this vendor
        $recentOrders = Order::where('customer_id', $vendor->id)
            ->with('items.product', 'customer')
            ->latest()
            ->take(5)
            ->get();

        // Get products - all active products with images
        $products = Product::where('status', 'active')
            ->with('images', 'category')
            ->latest()
            ->take(10)
            ->get();

        return view('vendor.dashboard', compact(
            'vendor',
            'totalSales',
            'activeProducts',
            'pendingOrders',
            'successRate',
            'recentOrders',
            'products'
        ));
    }

    /**
     * Show products management
     */
    public function products()
    {
        $vendor = Auth::user();
        
        // Show all active products that can be ordered
        $products = Product::where('status', 'active')
            ->with('images', 'category')
            ->paginate(15);

        return view('vendor.products.index', compact('products'));
    }

    /**
     * Show orders management
     */
    public function orders()
    {
        $vendor = Auth::user();
        
        // Get orders created by this vendor user
        $orders = Order::where('customer_id', $vendor->id)
            ->with('items.product', 'customer')
            ->latest()
            ->paginate(15);

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Show analytics
     */
    public function analytics()
    {
        $vendor = Auth::user();
        
        // Get sales data for the current month for this vendor
        $salesData = Order::where('customer_id', $vendor->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d');
            });

        return view('vendor.analytics', compact('salesData'));
    }

    /**
     * Show settings
     */
    public function settings()
    {
        $vendor = Auth::user();
        
        return view('vendor.settings', compact('vendor'));
    }
}
