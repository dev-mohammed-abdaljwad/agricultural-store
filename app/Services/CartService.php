<?php

declare(strict_types=1);

namespace App\Services;

class CartService
{
    /**
     * Get cart from session/cache.
     */
    public function getCart(int $customerId): array
    {
        return cache()->get("cart_{$customerId}", []);
    }

    /**
     * Add item to cart.
     */
    public function addItem(int $customerId, int $productId, int $quantity): array
    {
        $cart = $this->getCart($customerId);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        cache()->put("cart_{$customerId}", $cart, now()->addDays(7));
        return $cart;
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $customerId, int $productId): array
    {
        $cart = $this->getCart($customerId);

        unset($cart[$productId]);

        cache()->put("cart_{$customerId}", $cart, now()->addDays(7));
        return $cart;
    }

    /**
     * Update cart item quantity.
     */
    public function updateItem(int $customerId, int $productId, int $quantity): array
    {
        $cart = $this->getCart($customerId);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }

        cache()->put("cart_{$customerId}", $cart, now()->addDays(7));
        return $cart;
    }

    /**
     * Clear cart.
     */
    public function clearCart(int $customerId): array
    {
        cache()->forget("cart_{$customerId}");
        return [];
    }
}
