<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\OrderTracking;

class OrderTrackingService
{
    /**
     * Status titles mapping in Arabic.
     */
    private static array $statusTitles = [
        'placed' => 'تم استلام طلبك',
        'quote_pending' => 'جاري تجهيز عرض السعر',
        'quote_sent' => 'تم إرسال عرض السعر',
        'quote_accepted' => 'تم قبول عرض السعر',
        'paid' => 'تم تأكيد الدفع',
        'preparing' => 'جاري تجهيز طلبك',
        'out_for_delivery' => 'طلبك في الطريق إليك',
        'delivered' => 'تم تسليم طلبك بنجاح',
        'cancelled' => 'تم إلغاء الطلب',
        'returned' => 'تم إرجاع الطلب',
    ];

    /**
     * Record order status change in tracking.
     */
    public static function record(Order $order, string $status, string $description = null): OrderTracking
    {
        return OrderTracking::create([
            'order_id' => $order->id,
            'status' => $status,
            'title' => self::$statusTitles[$status] ?? $status,
            'description' => $description,
            'occurred_at' => now(),
        ]);
    }

    /**
     * Get status title in Arabic.
     */
    public static function getTitle(string $status): string
    {
        return self::$statusTitles[$status] ?? $status;
    }
}
