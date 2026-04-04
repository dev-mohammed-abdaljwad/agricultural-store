<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->withCount('products')
            ->with('subcategories')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('categories');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الفئة بنجاح',
                'category' => $category,
            ], 201);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'تم إضافة الفئة بنجاح');
    }

    /**
     * Show the form for editing a category
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update a category in storage
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $category->id,
            'icon' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('categories');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الفئة بنجاح',
                'category' => $category,
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * Delete a category
     */
    public function destroy(Request $request, Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'لا يمكن حذف فئة بها منتجات. يرجى نقل أو حذف المنتجات أولاً.',
                ], 400);
            }

            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'لا يمكن حذف فئة بها منتجات');
        }

        // Check if category has subcategories
        if ($category->subcategories()->count() > 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'لا يمكن حذف فئة بها فئات فرعية. يرجى حذف الفئات الفرعية أولاً.',
                ], 400);
            }

            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'لا يمكن حذف فئة بها فئات فرعية');
        }

        $category->delete();

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('categories');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الفئة بنجاح',
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }

    /**
     * Toggle category active status
     */
    public function toggleActive(Request $request, Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('categories');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $category->is_active ? 'تم تفعيل الفئة' : 'تم تعطيل الفئة',
                'is_active' => $category->is_active,
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', $category->is_active ? 'تم تفعيل الفئة' : 'تم تعطيل الفئة');
    }
}
