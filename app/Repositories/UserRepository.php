<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Find user by ID.
     */
    public function findById(int $id)
    {
        return User::find($id);
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create a new user.
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Update user by ID.
     */
    public function update(int $id, array $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
        }
        return $user;
    }

    /**
     * Get all users paginated.
     */
    public function paginate(int $perPage = 15)
    {
        return User::paginate($perPage);
    }

    /**
     * Get users by role.
     */
    public function getByRole(string $role, int $perPage = 15)
    {
        return User::where('role', $role)->paginate($perPage);
    }
}
