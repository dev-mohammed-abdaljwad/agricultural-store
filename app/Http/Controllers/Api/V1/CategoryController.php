<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all active categories.
     */
    public function index(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->with('subcategories')
            ->orderBy('name')
            ->get();

        return $this->successResponse(CategoryResource::collection($categories));
    }
}
