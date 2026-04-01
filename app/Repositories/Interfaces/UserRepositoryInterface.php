<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    /**
     * Find user by ID.
     */
    public function findById(int $id);

    /**
     * Find user by email.
     */
    public function findByEmail(string $email);

    /**
     * Create a new user.
     */
    public function create(array $data);

    /**
     * Update user by ID.
     */
    public function update(int $id, array $data);

    /**
     * Get all users paginated.
     */
    public function paginate(int $perPage = 15);

    /**
     * Get users by role.
     */
    public function getByRole(string $role, int $perPage = 15);
}
