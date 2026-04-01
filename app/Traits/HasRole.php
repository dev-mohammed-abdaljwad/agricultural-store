<?php

declare(strict_types=1);

namespace App\Traits;

trait HasRole
{
    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is admin (the single vendor/owner).
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is customer.
     */
    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }
}
