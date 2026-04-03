<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show home/landing page
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Redirect admin to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $totalOrders = Order::where('customer_id', $user->id)->count();
        $pendingOrders = Order::where('customer_id', $user->id)
            ->whereIn('status', ['pending', 'quote_sent', 'quote_accepted'])
            ->count();
        $completedOrders = Order::where('customer_id', $user->id)
            ->where('status', 'delivered')
            ->count();
        $cancelledOrders = Order::where('customer_id', $user->id)
            ->where('status', 'cancelled')
            ->count();
        
        $unreadMessages = Message::whereHas('conversation', function ($query) use ($user) {
            $query->where('customer_id', $user->id);
        })
        ->where('is_read', false)
        ->where('sender_type', 'admin')
        ->count();

        // Get recent orders with their quotes for total amount
        $recentOrders = Order::where('customer_id', $user->id)
            ->with(['quotes' => fn($q) => $q->where('status', 'accepted')->latest()])
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $acceptedQuote = $order->quotes->first();
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'created_at' => $order->created_at,
                    'status' => $order->status,
                    'status_label' => $order->getStatusLabel(),
                    'total_amount' => $acceptedQuote?->total_amount ?? 0,
                ];
            });

        // Get order statistics by status
        $ordersByStatus = Order::where('customer_id', $user->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('customer.dashboard', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders,
            'unreadMessages' => $unreadMessages,
            'recentOrders' => $recentOrders,
            'ordersByStatus' => $ordersByStatus,
        ]);
    }
}
