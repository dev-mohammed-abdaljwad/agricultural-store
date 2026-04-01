<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Interfaces\VendorRepositoryInterface;

class VendorService
{
    public function __construct(
        private VendorRepositoryInterface $vendorRepository,
    ) {}

    /**
     * Get vendor profile by user ID.
     */
    public function getVendorByUserId(int $userId)
    {
        return $this->vendorRepository->findByUserId($userId);
    }

    /**
     * Get pending vendors.
     */
    public function getPendingVendors(int $perPage = 15)
    {
        return $this->vendorRepository->getPending($perPage);
    }

    /**
     * Get approved vendors.
     */
    public function getApprovedVendors(int $perPage = 15)
    {
        return $this->vendorRepository->getApproved($perPage);
    }

    /**
     * Approve vendor.
     */
    public function approveVendor(int $vendorId): void
    {
        $this->vendorRepository->update($vendorId, [
            'status' => 'approved',
        ]);
    }

    /**
     * Suspend vendor.
     */
    public function suspendVendor(int $vendorId): void
    {
        $this->vendorRepository->update($vendorId, [
            'status' => 'suspended',
        ]);
    }
}
