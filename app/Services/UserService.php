<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Get user by ID.
     */
    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Get all users by role paginated.
     */
    public function getUsersByRole(string $role, int $perPage = 15)
    {
        return $this->userRepository->getByRole($role, $perPage);
    }

    /**
     * Get all users paginated.
     */
    public function getAllUsers(int $perPage = 15)
    {
        return $this->userRepository->paginate($perPage);
    }

    /**
     * Update user.
     */
    public function updateUser(int $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }
}
