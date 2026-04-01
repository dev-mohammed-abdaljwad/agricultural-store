<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterCustomerRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private AuthService $authService,
    ) {}

    /**
     * Register as customer.
     */
    public function registerCustomer(RegisterCustomerRequest $request): JsonResponse
    {
        $result = $this->authService->registerCustomer($request->validated());

        return $this->successResponse([
            'user' => UserResource::make($result['user']),
            'token' => $result['token'],
        ], 'Customer registered successfully.', 201);
    }

    /**
     * Login user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return $this->successResponse([
            'user' => UserResource::make($result['user']),
            'token' => $result['token'],
        ], 'Logged in successfully.');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse([], 'Logged out successfully.');
    }

    /**
     * Get authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->successResponse([
            'user' => UserResource::make($request->user()),
        ]);
    }
}
