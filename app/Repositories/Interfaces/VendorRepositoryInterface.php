<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface VendorRepositoryInterface
{
    /**
     * Find vendor profile by user ID.
     */
    public function findByUserId(int $userId);

    /**
     * Create vendor profile.
     */
    public function create(array $data);

    /**
     * Update vendor profile.
     */
    public function update(int $id, array $data);

    /**
     * Get pending vendors.
     */
    public function getPending(int $perPage = 15);

    /**
     * Get approved vendors.
     */
    public function getApproved(int $perPage = 15);
}
