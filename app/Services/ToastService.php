<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\RedirectResponse;

class ToastService
{
    /**
     * Toast types
     */
    public const TYPE_SUCCESS = 'success';
    public const TYPE_ERROR = 'error';
    public const TYPE_WARNING = 'warning';
    public const TYPE_INFO = 'info';

    /**
     * Flash success toast
     */
    public static function success(string $message, string $title = ''): void
    {
        session()->flash('toast', [
            'type' => self::TYPE_SUCCESS,
            'title' => $title ?: 'نجح!',
            'message' => $message,
        ]);
    }

    /**
     * Flash error toast
     */
    public static function error(string $message, string $title = ''): void
    {
        session()->flash('toast', [
            'type' => self::TYPE_ERROR,
            'title' => $title ?: 'خطأ!',
            'message' => $message,
        ]);
    }

    /**
     * Flash warning toast
     */
    public static function warning(string $message, string $title = ''): void
    {
        session()->flash('toast', [
            'type' => self::TYPE_WARNING,
            'title' => $title ?: 'تحذير',
            'message' => $message,
        ]);
    }

    /**
     * Flash info toast
     */
    public static function info(string $message, string $title = ''): void
    {
        session()->flash('toast', [
            'type' => self::TYPE_INFO,
            'title' => $title ?: 'معلومة',
            'message' => $message,
        ]);
    }

    /**
     * CRUD Helpers
     */

    /**
     * Created toast
     */
    public static function created(string $resourceName): void
    {
        self::success("تم إنشاء {$resourceName} بنجاح");
    }

    /**
     * Updated toast
     */
    public static function updated(string $resourceName): void
    {
        self::success("تم تحديث {$resourceName} بنجاح");
    }

    /**
     * Deleted toast
     */
    public static function deleted(string $resourceName): void
    {
        self::success("تم حذف {$resourceName} بنجاح");
    }

    /**
     * Creation failed toast
     */
    public static function creationFailed(string $resourceName, string $reason = ''): void
    {
        $message = "فشل إنشاء {$resourceName}";
        if ($reason) {
            $message .= ": {$reason}";
        }
        self::error($message);
    }

    /**
     * Update failed toast
     */
    public static function updateFailed(string $resourceName, string $reason = ''): void
    {
        $message = "فشل تحديث {$resourceName}";
        if ($reason) {
            $message .= ": {$reason}";
        }
        self::error($message);
    }

    /**
     * Deletion failed toast
     */
    public static function deletionFailed(string $resourceName, string $reason = ''): void
    {
        $message = "فشل حذف {$resourceName}";
        if ($reason) {
            $message .= ": {$reason}";
        }
        self::error($message);
    }
}
