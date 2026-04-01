<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductImageController extends Controller
{
    /**
     * Get all images for a product
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function index(Product $product): JsonResponse
    {
        $images = $product->images()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => $image->url,
                    'is_primary' => $image->is_primary,
                    'sort_order' => $image->sort_order,
                    'created_at' => $image->created_at?->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $images,
            'count' => $images->count(),
        ]);
    }

    /**
     * Get a specific product image
     *
     * @param Product $product
     * @param ProductImage $image
     * @return JsonResponse
     */
    public function show(Product $product, ProductImage $image): JsonResponse
    {
        // Verify the image belongs to this product
        if ($image->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found for this product',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $image->id,
                'product_id' => $image->product_id,
                'url' => $image->url,
                'is_primary' => $image->is_primary,
                'sort_order' => $image->sort_order,
                'created_at' => $image->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Upload images for a product
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_primary' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
        ]);

        try {
            $file = $request->file('image');
            
            // Store image in storage/app/public/products
            $path = $file->store('products', 'public');
            $fullUrl = '/storage/' . $path;

            // If marking as primary, unmark others
            if ($validated['is_primary'] ?? false) {
                $product->images()->update(['is_primary' => false]);
            }

            // Get next sort order if not provided
            $sortOrder = $validated['sort_order'] ?? $product->images->max('sort_order') + 1;

            $image = ProductImage::create([
                'product_id' => $product->id,
                'url' => $fullUrl,
                'is_primary' => $validated['is_primary'] ?? false,
                'sort_order' => $sortOrder,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'id' => $image->id,
                    'url' => $image->url,
                    'is_primary' => $image->is_primary,
                    'sort_order' => $image->sort_order,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update image properties
     *
     * @param Request $request
     * @param Product $product
     * @param ProductImage $image
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, Product $product, ProductImage $image): JsonResponse
    {
        // Verify the image belongs to this product
        if ($image->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found for this product',
            ], 404);
        }

        $validated = $request->validate([
            'is_primary' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
        ]);

        try {
            // If marking as primary, unmark others
            if ($validated['is_primary'] ?? false) {
                $product->images()->where('id', '!=', $image->id)->update(['is_primary' => false]);
            }

            $image->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully',
                'data' => [
                    'id' => $image->id,
                    'url' => $image->url,
                    'is_primary' => $image->is_primary,
                    'sort_order' => $image->sort_order,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a product image
     *
     * @param Product $product
     * @param ProductImage $image
     * @return JsonResponse
     */
    public function destroy(Product $product, ProductImage $image): JsonResponse
    {
        // Verify the image belongs to this product
        if ($image->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found for this product',
            ], 404);
        }

        try {
            // Extract path from URL and delete from storage
            if (str_contains($image->url, '/storage/')) {
                $filePath = str_replace('/storage/', '', $image->url);
                Storage::disk('public')->delete($filePath);
            }

            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get primary image for a product
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function primary(Product $product): JsonResponse
    {
        $image = $product->images()->where('is_primary', true)->first();

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'No primary image found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $image->id,
                'url' => $image->url,
                'is_primary' => $image->is_primary,
                'sort_order' => $image->sort_order,
            ],
        ]);
    }

    /**
     * Reorder images
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws ValidationException
     */
    public function reorder(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|integer|exists:product_images,id',
            'images.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($validated['images'] as $imageData) {
                // Verify image belongs to this product
                ProductImage::where('id', $imageData['id'])
                    ->where('product_id', $product->id)
                    ->update(['sort_order' => $imageData['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Images reordered successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reordering images: ' . $e->getMessage(),
            ], 500);
        }
    }
}
