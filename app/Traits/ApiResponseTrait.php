<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;

trait ApiResponseTrait
{
    /**
     * Return a successful response.
     */
    public function successResponse(
        mixed $data,
        string $message = '',
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return an error response.
     */
    public function errorResponse(
        string $message,
        int $code = 400,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Return a paginated response.
     */
    public function paginatedResponse(
        Paginator $resource,
        string $message = ''
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resource->items(),
            'pagination' => [
                'total' => $resource->total(),
                'per_page' => $resource->perPage(),
                'current_page' => $resource->currentPage(),
                'last_page' => $resource->lastPage(),
                'has_more' => $resource->hasMorePages(),
            ],
        ], 200);
    }
}
