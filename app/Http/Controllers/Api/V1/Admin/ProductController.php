<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\SyncSpecsRequest;
use App\Http\Resources\ProductAdminResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpec;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    /**
     * List all products with supplier fields (admin).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('supplier_name', 'like', '%' . $request->search . '%');
        }

        $products = $query->with('category', 'images', 'specs')
            ->latest()
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(ProductAdminResource::collection($products));
    }

    /**
     * Create product (admin).
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return $this->successResponse(
            ProductAdminResource::make($product->load('category', 'images', 'specs')),
            'Product created successfully.',
            201
        );
    }

    /**
     * Update product (admin).
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return $this->successResponse(
            ProductAdminResource::make($product->load('category', 'images', 'specs')),
            'Product updated successfully.'
        );
    }

    /**
     * Delete product (admin).
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->successResponse([], 'Product deleted successfully.');
    }

    /**
     * Add images to product (admin).
     */
    public function addImages(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.url' => 'required|url',
            'images.*.is_primary' => 'boolean',
            'images.*.sort_order' => 'integer',
        ]);

        foreach ($request->input('images') as $imageData) {
            ProductImage::create([
                'product_id' => $product->id,
                'url' => $imageData['url'],
                'is_primary' => $imageData['is_primary'] ?? false,
                'sort_order' => $imageData['sort_order'] ?? 0,
            ]);
        }

        return $this->successResponse(
            ProductAdminResource::make($product->refresh()->load('images', 'specs')),
            'Images added successfully.',
            201
        );
    }

    /**
     * Sync specs (replace all) (admin).
     */
    public function syncSpecs(SyncSpecsRequest $request, Product $product): JsonResponse
    {
        // Delete existing specs
        $product->specs()->delete();

        // Create new specs
        foreach ($request->input('specs') as $specData) {
            ProductSpec::create([
                'product_id' => $product->id,
                'key' => $specData['key'],
                'value' => $specData['value'],
                'sort_order' => $specData['sort_order'] ?? 0,
            ]);
        }

        return $this->successResponse(
            ProductAdminResource::make($product->refresh()->load('images', 'specs')),
            'Specs updated successfully.'
        );
    }
}
