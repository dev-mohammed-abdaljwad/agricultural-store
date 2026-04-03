<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderStatusController extends Controller
{
    /**
     * Get current order status
     * Returns: { status, status_label, updated_at }
     */
    public function getStatus(Order $order): JsonResponse
    {
        // Verify user owns this order
        if ($order->customer_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Refresh order from database to get latest status
        $order->refresh();

        return response()->json([
            'status' => $order->status,
            'status_label' => $order->getStatusLabel(),
            'updated_at' => $order->updated_at,
        ]);
    }
}
