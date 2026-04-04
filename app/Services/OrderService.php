<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Conversation;
use App\Models\Message;
use App\Jobs\NotifyAdminOfNewOrder;
use App\Events\OrderStatusUpdated;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * Place a new order with items.
     * Auto-creates conversation and first tracking entry.
     */
    public function placeOrder(User $customer, array $data): Order
    {
        // Validate items exist and are active
        $itemsData = $data['items'] ?? [];
        $validatedItems = [];

        foreach ($itemsData as $item) {
            $product = \App\Models\Product::findOrFail($item['product_id']);

            if ($product->status !== 'active') {
                throw ValidationException::withMessages([
                    'items' => ["Product {$product->name} is not available."],
                ]);
            }

            $validatedItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => null,  // Will be set after quote
                'total_price' => null,
            ];
        }

        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'status' => 'placed',
            'delivery_address' => $data['delivery_address'],
            'delivery_governorate' => $data['delivery_governorate'],
            'payment_method' => $data['payment_method'] ?? 'cod',
        ]);

        // Add items
        foreach ($validatedItems as $item) {
            OrderItem::create($item + ['order_id' => $order->id]);
        }

        // Auto-create conversation
        Conversation::create([
            'order_id' => $order->id,
            'customer_id' => $customer->id,
        ]);

        // Auto-insert first tracking entry
        OrderTrackingService::record($order, 'placed');

        // Dispatch notification job
        NotifyAdminOfNewOrder::dispatch($order);

        return $order->load('items', 'conversation');
    }

    /**
     * Get customer's orders.
     */
    public function getCustomerOrders(User $customer, int $perPage = 15)
    {
        return $customer->orders()->with('items', 'activeQuote', 'tracking', 'conversation')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all orders (admin).
     */
    public function getAllOrders(string $status = null, int $perPage = 15)
    {
        $query = Order::with('customer', 'items', 'activeQuote', 'tracking', 'conversation')
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get single order.
     */
    public function getOrder(Order $order)
    {
        return $order->load('items.product', 'activeQuote.items', 'tracking', 'conversation.messages');
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Order $order, string $newStatus, string $description = null): Order
    {
        $oldStatus = $order->status;
        $order->update(['status' => $newStatus]);

        // Add tracking entry
        OrderTrackingService::record($order, $newStatus);
        
        // Broadcast status change via Pusher
        OrderStatusUpdated::dispatch($order, $oldStatus, $newStatus);

        return $order->refresh()->load('items', 'activeQuote', 'tracking', 'conversation');
    }
}
