<?php

declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string|max:500',
            'delivery_governorate' => 'required|string|max:100',
            'payment_method' => 'nullable|in:cod,online',
        ];
    }
}
