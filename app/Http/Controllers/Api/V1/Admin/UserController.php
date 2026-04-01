<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private UserService $userService,
    ) {}

    /**
     * Get all users paginated.
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers(15);

        return $this->paginatedResponse($users, 'Users retrieved successfully.');
    }

    /**
     * Get users by specific role.
     */
    public function getByRole(string $role): JsonResponse
    {
        $users = $this->userService->getUsersByRole($role, 15);

        return $this->paginatedResponse($users, "Users with role '{$role}' retrieved successfully.");
    }

    /**
     * Get single user by ID.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return $this->errorResponse('User not found.', 404);
        }

        return $this->successResponse(UserResource::make($user), 'User retrieved successfully.');
    }

    /**
     * Suspend user.
     */
    public function suspend(int $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, ['status' => 'suspended']);

        if (!$user) {
            return $this->errorResponse('User not found.', 404);
        }

        return $this->successResponse(UserResource::make($user), 'User suspended successfully.');
    }

    /**
     * Activate user.
     */
    public function activate(int $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, ['status' => 'active']);

        if (!$user) {
            return $this->errorResponse('User not found.', 404);
        }

        return $this->successResponse(UserResource::make($user), 'User activated successfully.');
    }
}
