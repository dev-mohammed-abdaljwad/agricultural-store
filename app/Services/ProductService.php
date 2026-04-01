<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    /**
     * Get product by ID.
     */
    public function getProductById(int $id)
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Get products by vendor.
     */
    public function getVendorProducts(int $vendorId, int $perPage = 15)
    {
        return $this->productRepository->getByVendor($vendorId, $perPage);
    }

    /**
     * Get products by category.
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 15)
    {
        return $this->productRepository->getByCategory($categoryId, $perPage);
    }

    /**
     * Get all active products.
     */
    public function getActiveProducts(int $perPage = 15)
    {
        return $this->productRepository->getActive($perPage);
    }

    /**
     * Search products.
     */
    public function searchProducts(string $query, int $perPage = 15)
    {
        return $this->productRepository->search($query, $perPage);
    }

    /**
     * Create product.
     */
    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update product.
     */
    public function updateProduct(int $id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    /**
     * Delete product.
     */
    public function deleteProduct(int $id)
    {
        return $this->productRepository->delete($id);
    }
}
