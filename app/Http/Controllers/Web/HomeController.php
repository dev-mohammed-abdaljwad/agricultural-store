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
            ->whereIn('status', ['placed', 'quote_pending', 'quote_sent'])
            ->count();
        $completedOrders = Order::where('customer_id', $user->id)
            ->where('status', 'delivered')
            ->count();
        
        $unreadMessages = Message::whereHas('conversation', function ($query) use ($user) {
            $query->where('customer_id', $user->id);
        })
        ->where('is_read', false)
        ->where('sender_type', 'admin')
        ->count();

        $recentOrders = Order::where('customer_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'unreadMessages' => $unreadMessages,
            'recentOrders' => $recentOrders,
        ]);
    }
}
