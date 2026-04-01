<?php

declare(strict_types=1);

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string|min:1|max:5000',
        ];
    }
}
