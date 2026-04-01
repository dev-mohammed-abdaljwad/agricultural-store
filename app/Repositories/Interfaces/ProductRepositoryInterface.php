<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    /**
     * Find product by ID.
     */
    public function findById(int $id);

    /**
     * Create product.
     */
    public function create(array $data);

    /**
     * Update product.
     */
    public function update(int $id, array $data);

    /**
     * Delete product.
     */
    public function delete(int $id);

    /**
     * Get products by vendor.
     */
    public function getByVendor(int $vendorId, int $perPage = 15);

    /**
     * Get products by category.
     */
    public function getByCategory(int $categoryId, int $perPage = 15);

    /**
     * Get all active products paginated.
     */
    public function getActive(int $perPage = 15);

    /**
     * Search products.
     */
    public function search(string $query, int $perPage = 15);
}
