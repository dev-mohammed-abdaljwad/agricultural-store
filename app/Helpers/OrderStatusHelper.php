<?php

declare(strict_types=1);

namespace App\Helpers;

class OrderStatusHelper
{
    /**
     * Order status translations
     */
    private static array $statusTranslations = [
        'pending' => 'قيد المراجعة',
        'quote_pending' => 'في انتظار عرض السعر',
        'quote_sent' => 'تم إرسال عرض السعر',
        'quote_accepted' => 'تم التأكيد',
        'quote_rejected' => 'تم رفض العرض',
        'shipped' => 'قيد الشحن',
        'delivered' => 'تم التسليم',
        'rejected' => 'تم الرفض',
        'cancelled' => 'تم الإلغاء',
    ];

    /**
     * Get Arabic translation of order status
     */
    public static function translate(string $status): string
    {
        return self::$statusTranslations[$status] ?? $status;
    }

    /**
     * Get color class for status badge
     */
    public static function getColorClass(string $status): string
    {
        return match($status) {
            'pending' => 'bg-warning text-on-warning',
            'quote_pending', 'quote_sent' => 'bg-info text-on-info',
            'quote_accepted', 'quote_rejected', 'shipped' => 'bg-secondary text-on-secondary',
            'delivered' => 'bg-primary text-on-primary',
            'rejected', 'cancelled' => 'bg-error text-on-error',
            default => 'bg-surface-container text-on-surface',
        };
    }

    /**
     * Get all status translations
     */
    public static function all(): array
    {
        return self::$statusTranslations;
    }
}
