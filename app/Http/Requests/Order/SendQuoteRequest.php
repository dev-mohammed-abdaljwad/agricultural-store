<?php

declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class SendQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.unit_price' => 'required|numeric|min:0.01',
            'delivery_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'expires_in_hours' => 'nullable|integer|min:1|max:720',
        ];
    }
}
