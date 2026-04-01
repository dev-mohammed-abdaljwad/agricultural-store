<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Find product by ID.
     */
    public function findById(int $id)
    {
        return Product::with(['vendor', 'category'])->find($id);
    }

    /**
     * Create product.
     */
    public function create(array $data)
    {
        return Product::create($data);
    }

    /**
     * Update product.
     */
    public function update(int $id, array $data)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($data);
        }
        return $product;
    }

    /**
     * Delete product.
     */
    public function delete(int $id)
    {
        return Product::destroy($id);
    }

    /**
     * Get products by vendor.
     */
    public function getByVendor(int $vendorId, int $perPage = 15)
    {
        return Product::where('vendor_id', $vendorId)
            ->with(['category', 'vendor'])
            ->paginate($perPage);
    }

    /**
     * Get products by category.
     */
    public function getByCategory(int $categoryId, int $perPage = 15)
    {
        return Product::where('category_id', $categoryId)
            ->where('status', 'active')
            ->with(['vendor', 'category'])
            ->paginate($perPage);
    }

    /**
     * Get all active products paginated.
     */
    public function getActive(int $perPage = 15)
    {
        return Product::where('status', 'active')
            ->with(['vendor', 'category'])
            ->paginate($perPage);
    }

    /**
     * Search products.
     */
    public function search(string $query, int $perPage = 15)
    {
        return Product::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['vendor', 'category'])
            ->paginate($perPage);
    }
}
