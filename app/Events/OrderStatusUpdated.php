<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Order data
     */
    public Order $order;
    public string $oldStatus;
    public string $newStatus;
    public string $statusLabel;
    public array $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->statusLabel = $order->getStatusLabel();
        
        // Build notification message based on who made the change
        $this->notification = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $newStatus,
            'status_label' => $this->statusLabel,
            'customer_id' => $order->customer_id,
            'timestamp' => now()->toIso8601String(),
            'message' => $this->buildNotificationMessage($newStatus),
        ];
    }

    /**
     * Build notification message based on status
     */
    private function buildNotificationMessage(string $status): string
    {
        $messages = [
            'placed' => 'تم استقبال طلب جديد',
            'quote_pending' => 'قيد تحضير عرض سعر',
            'quote_sent' => 'تم إرسال عرض سعر',
            'quote_accepted' => 'تم قبول العرض من قبل العميل',
            'quote_rejected' => 'تم رفض العرض من قبل العميل',
            'paid' => 'تم تأكيد الدفع',
            'preparing' => 'جاري تجهيز الشحنة',
            'out_for_delivery' => 'الطلب في الطريق',
            'delivered' => 'تم استلام الطلب',
            'cancelled' => 'تم إلغاء الطلب',
            'returned' => 'تم إرجاع الطلب',
        ];

        return $messages[$status] ?? 'تم تحديث حالة الطلب: ' . $this->statusLabel;
    }

    /**
     * Get the channels the event should broadcast on.
     * 
     * Broadcasts to:
     * 1. Admin notification channel (for admin dashboard)
     * 2. Customer notification channel (for customer pages)
     */
    public function broadcastOn(): array
    {
        return [
            // Broadcast to admin for notification
            new PrivateChannel('admin.notifications'),
            // Broadcast to customer for their pages
            new PrivateChannel('customer.notifications.' . $this->order->customer_id),
            // Broadcast to specific order channel
            new PrivateChannel('order.' . $this->order->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order-status-updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return $this->notification;
    }
}
