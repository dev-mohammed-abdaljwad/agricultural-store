<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class SyncSpecsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'specs' => 'required|array',
            'specs.*.key' => 'required|string|max:255',
            'specs.*.value' => 'required|string|max:255',
            'specs.*.sort_order' => 'nullable|integer',
        ];
    }
}
