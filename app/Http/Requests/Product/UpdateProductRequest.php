<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'min_order_qty' => 'nullable|integer|min:1',
            'is_certified' => 'nullable|boolean',
            'data_sheet_url' => 'nullable|url',
            'usage_instructions' => 'nullable|string',
            'safety_instructions' => 'nullable|string',
            'manufacturer_info' => 'nullable|string',
            'expert_tip' => 'nullable|string',
            'expert_name' => 'nullable|string|max:255',
            'expert_title' => 'nullable|string|max:255',
            'expert_image_url' => 'nullable|url',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_code' => 'nullable|string|max:100',
            'status' => 'sometimes|in:active,inactive',
        ];
    }
}
