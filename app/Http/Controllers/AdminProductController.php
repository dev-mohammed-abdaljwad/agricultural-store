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
            'chemical_composition' => 'nullable|string',
            'package_sizes' => 'nullable|string',
            'how_it_works' => 'nullable|string',
            'extended_description' => 'nullable|string',
            'frac_group' => 'nullable|string|max:100',
            'benefits' => 'nullable|string',
            'usage_recommendations' => 'nullable|string',
            'safety_notice' => 'nullable|string',
            'registration_number' => 'nullable|string|max:100',
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
            'crops' => 'nullable|array',
            'crops.*' => 'exists:crops,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? '',
            'chemical_composition' => $validated['chemical_composition'] ?? null,
            'package_sizes' => $validated['package_sizes'] ?? null,
            'how_it_works' => $validated['how_it_works'] ?? null,
            'extended_description' => $validated['extended_description'] ?? null,
            'frac_group' => $validated['frac_group'] ?? null,
            'benefits' => $validated['benefits'] ?? null,
            'usage_recommendations' => $validated['usage_recommendations'] ?? null,
            'safety_notice' => $validated['safety_notice'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
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

        // Attach crops if provided
        if (!empty($validated['crops'])) {
            $product->crops()->attach($validated['crops']);
        }

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
            'chemical_composition' => 'nullable|string',
            'package_sizes' => 'nullable|string',
            'how_it_works' => 'nullable|string',
            'extended_description' => 'nullable|string',
            'frac_group' => 'nullable|string|max:100',
            'benefits' => 'nullable|string',
            'usage_recommendations' => 'nullable|string',
            'safety_notice' => 'nullable|string',
            'registration_number' => 'nullable|string|max:100',
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
            'crops' => 'nullable|array',
            'crops.*' => 'exists:crops,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $product->update([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? '',
            'chemical_composition' => $validated['chemical_composition'] ?? null,
            'package_sizes' => $validated['package_sizes'] ?? null,
            'how_it_works' => $validated['how_it_works'] ?? null,
            'extended_description' => $validated['extended_description'] ?? null,
            'frac_group' => $validated['frac_group'] ?? null,
            'benefits' => $validated['benefits'] ?? null,
            'usage_recommendations' => $validated['usage_recommendations'] ?? null,
            'safety_notice' => $validated['safety_notice'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
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

        // Sync crops if provided
        if (array_key_exists('crops', $validated)) {
            $product->crops()->sync($validated['crops'] ?? []);
        }

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
     * Remove the specified product from storage AND RETURN TO PRODUCT INDEX PAGE 
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

        // Fallback to redirect for non-AJAX requests - return to admin products index
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}
