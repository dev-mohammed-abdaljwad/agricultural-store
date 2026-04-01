<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private CartService $cartService,
    ) {}

    /**
     * Get current cart.
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart($request->user()->id);

        return $this->successResponse(['items' => $cart], 'Cart retrieved successfully.');
    }

    /**
     * Add item to cart.
     */
    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->addItem(
            customerId: $request->user()->id,
            productId: $validated['product_id'],
            quantity: $validated['quantity'],
        );

        return $this->successResponse(['items' => $cart], 'Item added to cart successfully.');
    }

    /**
     * Update cart item.
     */
    public function updateItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->updateItem(
            customerId: $request->user()->id,
            productId: $validated['product_id'],
            quantity: $validated['quantity'],
        );

        return $this->successResponse(['items' => $cart], 'Cart updated successfully.');
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(Request $request, int $productId): JsonResponse
    {
        $cart = $this->cartService->removeItem($request->user()->id, $productId);

        return $this->successResponse(['items' => $cart], 'Item removed from cart successfully.');
    }

    /**
     * Clear cart.
     */
    public function clear(Request $request): JsonResponse
    {
        $this->cartService->clearCart($request->user()->id);

        return $this->successResponse([], 'Cart cleared successfully.');
    }
}
