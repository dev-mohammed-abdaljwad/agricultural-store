<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show products marketplace
     */
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();

        $query = Product::where('status', 'active');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $category = Category::find($request->category);
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search by product name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->with(['images', 'category'])
            ->paginate(12);

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Show product details
     */
    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $product->load(['images', 'specs', 'category']);

        $categories = Category::where('is_active', true)->get();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with('images')
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'categories' => $categories,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
