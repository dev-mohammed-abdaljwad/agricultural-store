<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private ProductService $productService,
    ) {}

    /**
     * Get vendor's products.
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->getVendorProducts($request->user()->id, 15);

        return $this->paginatedResponse($products, 'Your products retrieved successfully.');
    }

    /**
     * Create product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct([
            'vendor_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        return $this->successResponse(
            ProductResource::make($product),
            'Product created successfully.',
            201
        );
    }

    /**
     * Get single product.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product || $product->vendor_id !== $request->user()->id) {
            return $this->errorResponse('Product not found.', 404);
        }

        return $this->successResponse(ProductResource::make($product), 'Product retrieved successfully.');
    }

    /**
     * Update product.
     */
    public function update(int $id, UpdateProductRequest $request): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product || $product->vendor_id !== $request->user()->id) {
            return $this->errorResponse('Product not found.', 404);
        }

        $product = $this->productService->updateProduct($id, $request->validated());

        return $this->successResponse(ProductResource::make($product), 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function destroy(int $id, Request $request): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product || $product->vendor_id !== $request->user()->id) {
            return $this->errorResponse('Product not found.', 404);
        }

        $this->productService->deleteProduct($id);

        return $this->successResponse([], 'Product deleted successfully.');
    }
}
