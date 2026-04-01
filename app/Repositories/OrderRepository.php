<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Find order by ID.
     */
    public function findById(int $id)
    {
        return Order::with(['customer', 'vendor', 'items.product'])->find($id);
    }

    /**
     * Create order.
     */
    public function create(array $data)
    {
        return Order::create($data);
    }

    /**
     * Update order.
     */
    public function update(int $id, array $data)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update($data);
        }
        return $order;
    }

    /**
     * Get customer orders.
     */
    public function getCustomerOrders(int $customerId, int $perPage = 15)
    {
        return Order::where('customer_id', $customerId)
            ->with(['vendor', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get vendor orders.
     */
    public function getVendorOrders(int $vendorId, int $perPage = 15)
    {
        return Order::where('vendor_id', $vendorId)
            ->with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get orders by status.
     */
    public function getByStatus(string $status, int $perPage = 15)
    {
        return Order::where('status', $status)
            ->with(['customer', 'vendor', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Add items to order.
     */
    public function addItems(int $orderId, array $items)
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);
        }
        return $this->findById($orderId);
    }
}
