<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'phone' => 'required|string|max:20|unique:users,phone',
            'customer_type' => 'nullable|in:farmer,trader',
            'governorate' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ];
    }
}
