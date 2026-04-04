<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Message;
use App\Models\OrderTracking;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminOrderController extends Controller
{
    /**
     * Display all orders with filters and search
     */
    public function index(Request $request): View
    {
        $query = Order::with(['customer', 'items.product'])
            ->latest();
        
        // Filter by status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Search by order number or customer name
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
            });
        }
        
        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->paginate(15);
        $statuses = [
            'placed' => 'تحت المراجعة',
            'quote_sent' => 'تم إرسال عرض السعر',
            'confirmed' => 'تم التأكيد',
            'rejected' => 'تم الرفض',
            'cancelled' => 'ملغى',
            'completed' => 'مكتمل'
        ];
        
        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Show order details page
     */
    public function show(Order $order): View
    {
        $order->load(['customer', 'items.product', 'tracking', 'conversation.messages']);
        
        $statuses = [
            'placed' => 'تحت المراجعة',
            'quote_pending' => 'في انتظار عرض السعر',
            'quote_sent' => 'تم إرسال عرض السعر',
            'quote_accepted' => 'تم قبول العرض',
            'quote_rejected' => 'تم رفض العرض',
            'paid' => 'تم الدفع',
            'preparing' => 'قيد التحضير',
            'out_for_delivery' => 'جاري التوصيل',
            'delivered' => 'تم الاستلام',
            'cancelled' => 'ملغى',
            'returned' => 'تم الإرجاع'
        ];
        
        $tracking = $order->tracking;
        $messages = $order->conversation?->messages ?? collect();
        
        return view('admin.orders.show', compact('order', 'statuses', 'tracking', 'messages'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:placed,quote_pending,quote_sent,quote_accepted,quote_rejected,paid,preparing,out_for_delivery,delivered,cancelled,returned',
            'notes' => 'nullable|string'
        ]);
        
        $oldStatus = $order->status;
        $order->update([
            'status' => $validated['status'],
        ]);
        
        // Add notes if provided
        if ($validated['notes'] ?? null) {
            $order->update(['admin_notes' => $validated['notes']]);
        }
        
        // Log to tracking
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => $validated['status'],
            'title' => $this->getStatusTitle($validated['status']),
            'description' => "تم تحديث الحالة من {$this->getStatusTitle($oldStatus)} إلى {$this->getStatusTitle($validated['status'])}",
            'occurred_at' => now()
        ]);
        
        // Broadcast status change via Pusher
        OrderStatusUpdated::dispatch($order, $oldStatus, $validated['status']);
        
        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * Update delivery information
     */
    public function updateDelivery(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|min:10',
            'delivery_governorate' => 'required|string',
            'delivery_phone' => 'required|regex:/^(01|002)[0-9]{9}$/',
            'delivery_date' => 'nullable|date|after:today'
        ]);
        
        $order->update($validated);
        
        // Log to tracking
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'title' => 'تم تحديث بيانات التوصيل',
            'description' => 'تم تحديث عنوان التوصيل ورقم الهاتف من قبل المسؤول',
            'occurred_at' => now()
        ]);
        
        return back()->with('success', 'تم تحديث معلومات التوصيل بنجاح');
    }

    /**
     * Cancel or reject order
     */
    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10',
            'refund_amount' => 'nullable|numeric|min:0'
        ]);
        
        $order->update([
            'status' => 'cancelled',
            'admin_notes' => $validated['reason']
        ]);
        
        // Log to tracking
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'title' => 'تم إلغاء الطلب',
            'description' => $validated['reason'],
            'occurred_at' => now()
        ]);
        
        return back()->with('success', 'تم إلغاء الطلب بنجاح');
    }

    /**
     * Send order update notification to customer
     */
    public function sendNotification(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:10'
        ]);
        
        // Create message in conversation
        if (!$order->conversation) {
            $order->conversation()->create([
                'participant_id' => $order->customer_id,
                'initiated_by' => 'admin'
            ]);
        }
        
        Message::create([
            'conversation_id' => $order->conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'admin',
            'content' => $validated['message'],
            'is_read' => false
        ]);
        
        // TODO: Send email notification to customer
        // Mail::to($order->customer->email)->send(new OrderUpdate($order, $message));
        
        return back()->with('success', 'تم إرسال الرسالة للعميل بنجاح');
    }

    /**
     * Get status label in Arabic
     */
    private function getStatusTitle(string $status): string
    {
        return match($status) {
            'placed' => 'تم عمل الطلب',
            'quote_pending' => 'في انتظار عرض السعر',
            'quote_sent' => 'تم إرسال عرض السعر',
            'quote_accepted' => 'تم قبول العرض',
            'quote_rejected' => 'تم رفض العرض',
            'paid' => 'تم الدفع',
            'preparing' => 'قيد التحضير',
            'out_for_delivery' => 'جاري التوصيل',
            'delivered' => 'تم الاستلام',
            'cancelled' => 'تم إلغاء الطلب',
            'returned' => 'تم الإرجاع',
            default => $status,
        };
    }
}
