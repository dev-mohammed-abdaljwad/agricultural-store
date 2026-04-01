<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Register a customer user.
     */
    public function registerCustomer(array $data): array
    {
        // Check if email already exists
        if ($this->userRepository->findByEmail($data['email'])) {
            throw ValidationException::withMessages([
                'email' => ['Email already registered.'],
            ]);
        }

        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'customer_type' => $data['customer_type'] ?? null,
            'governorate' => $data['governorate'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => 'customer',
            'status' => 'active',
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Login user with credentials.
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        if ($user->status === 'suspended') {
            throw ValidationException::withMessages([
                'email' => ['Account is suspended.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout user by revoking current token.
     */
    public function logout(User $user): bool
    {
        return $user->currentAccessToken()->delete();
    }

}
