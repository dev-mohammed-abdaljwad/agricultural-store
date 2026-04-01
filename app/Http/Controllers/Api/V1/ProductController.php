<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Product;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get paginated products (public).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::where('status', 'active');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->with('category', 'primaryImage', 'specs')
            ->latest()
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(ProductResource::collection($products));
    }

    /**
     * Get single product (public).
     */
    public function show(Product $product): JsonResponse
    {
        if ($product->status !== 'active') {
            return $this->errorResponse('Product not found.', 404);
        }

        $product->load('category', 'images', 'specs', 'primaryImage');

        return $this->successResponse(ProductResource::make($product));
    }
}
