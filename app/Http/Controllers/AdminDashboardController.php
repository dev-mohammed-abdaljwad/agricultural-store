<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Message;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Only admins can access this
        if (!$user || $user->role !== 'admin') {
            abort(403, 'غير مصرح بالوصول إلى هذه الصفحة');
        }

        // Get platform-wide statistics
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalVendors = User::whereIn('customer_type', ['trader'])
            ->orWhere('role', 'vendor')
            ->count();
        
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount') ?? 0;
        
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        
        $pendingOrders = Order::whereIn('status', ['pending', 'quote_pending'])
            ->count();
        
        $completedOrders = Order::where('status', 'delivered')
            ->count();
        
        $unreadMessages = Message::where('is_read', false)
            ->where('sender_type', '!=', 'admin')
            ->count();

        // Get recent orders for monitoring
        $recentOrders = Order::with('customer', 'items.product')
            ->latest()
            ->limit(10)
            ->get();

        // Get recent users
        $recentUsers = User::where('role', '!=', 'admin')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalVendors',
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'activeProducts',
            'pendingOrders',
            'completedOrders',
            'unreadMessages',
            'recentOrders',
            'recentUsers'
        ));
    }

    /**
     * Show products management
     */
    public function products()
    {
        $products = Product::with('category', 'images')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show orders management
     */
    public function orders()
    {
        $orders = Order::with('customer', 'items.product')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show users/vendors management
     */
    public function users()
    {
        $users = User::where('role', '!=', 'admin')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show analytics
     */
    public function analytics()
    {
        // Sales data for the current month
        $salesData = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d');
            });

        // Top products by order count
        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();

        // Vendor performance
        $vendorStats = User::where('customer_type', 'trader')
            ->orWhere('role', 'vendor')
            ->withCount('orders')
            ->latest('orders_count')
            ->limit(5)
            ->get();

        return view('admin.analytics', compact('salesData', 'topProducts', 'vendorStats'));
    }

    /**
     * Show settings
     */
    public function settings()
    {
        $admin = Auth::user();
        
        return view('admin.settings', compact('admin'));
    }
}
