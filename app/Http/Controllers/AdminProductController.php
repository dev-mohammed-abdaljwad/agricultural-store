<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'min_order_qty' => 'nullable|integer|min:1',
            'is_certified' => 'nullable|boolean',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_code' => 'nullable|string|max:100',
            'data_sheet_url' => 'nullable|url',
            'usage_instructions' => 'nullable|string',
            'safety_instructions' => 'nullable|string',
            'manufacturer_info' => 'nullable|string',
            'expert_tip' => 'nullable|string',
            'expert_name' => 'nullable|string|max:255',
            'expert_title' => 'nullable|string|max:255',
            'expert_image_url' => 'nullable|url',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? '',
            'unit' => $validated['unit'] ?? null,
            'min_order_qty' => $validated['min_order_qty'] ?? 1,
            'is_certified' => $validated['is_certified'] ?? false,
            'supplier_name' => $validated['supplier_name'] ?? null,
            'supplier_code' => $validated['supplier_code'] ?? null,
            'data_sheet_url' => $validated['data_sheet_url'] ?? null,
            'usage_instructions' => $validated['usage_instructions'] ?? null,
            'safety_instructions' => $validated['safety_instructions'] ?? null,
            'manufacturer_info' => $validated['manufacturer_info'] ?? null,
            'expert_tip' => $validated['expert_tip'] ?? null,
            'expert_name' => $validated['expert_name'] ?? null,
            'expert_title' => $validated['expert_title'] ?? null,
            'expert_image_url' => $validated['expert_image_url'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images()->create([
                'url' => '/storage/' . $path,
                'is_primary' => true,
            ]);
        }

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('products');
        
        // Return JSON response for AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج بنجاح',
                'product' => $product,
            ], 201);
        }

        // Fallback to redirect for non-AJAX requests
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'min_order_qty' => 'nullable|integer|min:1',
            'is_certified' => 'nullable|boolean',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_code' => 'nullable|string|max:100',
            'data_sheet_url' => 'nullable|url',
            'usage_instructions' => 'nullable|string',
            'safety_instructions' => 'nullable|string',
            'manufacturer_info' => 'nullable|string',
            'expert_tip' => 'nullable|string',
            'expert_name' => 'nullable|string|max:255',
            'expert_title' => 'nullable|string|max:255',
            'expert_image_url' => 'nullable|url',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $product->update([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? '',
            'unit' => $validated['unit'] ?? null,
            'min_order_qty' => $validated['min_order_qty'] ?? 1,
            'is_certified' => $validated['is_certified'] ?? false,
            'supplier_name' => $validated['supplier_name'] ?? null,
            'supplier_code' => $validated['supplier_code'] ?? null,
            'data_sheet_url' => $validated['data_sheet_url'] ?? null,
            'usage_instructions' => $validated['usage_instructions'] ?? null,
            'safety_instructions' => $validated['safety_instructions'] ?? null,
            'manufacturer_info' => $validated['manufacturer_info'] ?? null,
            'expert_tip' => $validated['expert_tip'] ?? null,
            'expert_name' => $validated['expert_name'] ?? null,
            'expert_title' => $validated['expert_title'] ?? null,
            'expert_image_url' => $validated['expert_image_url'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images()->create([
                'url' => '/storage/' . $path,
                'is_primary' => true,
            ]);
        }

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('products');

        // Return JSON response for AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المنتج بنجاح',
                'product' => $product,
            ], 200);
        }

        // Fallback to redirect for non-AJAX requests
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('products');

        // Return JSON response for AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج بنجاح',
            ], 200);
        }

        // Fallback to redirect for non-AJAX requests
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}
