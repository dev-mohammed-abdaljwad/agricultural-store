<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    /**
     * Find order by ID.
     */
    public function findById(int $id);

    /**
     * Create order.
     */
    public function create(array $data);

    /**
     * Update order.
     */
    public function update(int $id, array $data);

    /**
     * Get customer orders.
     */
    public function getCustomerOrders(int $customerId, int $perPage = 15);

    /**
     * Get vendor orders.
     */
    public function getVendorOrders(int $vendorId, int $perPage = 15);

    /**
     * Get orders by status.
     */
    public function getByStatus(string $status, int $perPage = 15);

    /**
     * Add items to order.
     */
    public function addItems(int $orderId, array $items);
}
