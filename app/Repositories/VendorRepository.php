<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\VendorProfile;
use App\Repositories\Interfaces\VendorRepositoryInterface;

class VendorRepository implements VendorRepositoryInterface
{
    /**
     * Find vendor profile by user ID.
     */
    public function findByUserId(int $userId)
    {
        return VendorProfile::where('user_id', $userId)->first();
    }

    /**
     * Create vendor profile.
     */
    public function create(array $data)
    {
        return VendorProfile::create($data);
    }

    /**
     * Update vendor profile.
     */
    public function update(int $id, array $data)
    {
        $vendor = VendorProfile::find($id);
        if ($vendor) {
            $vendor->update($data);
        }
        return $vendor;
    }

    /**
     * Get pending vendors.
     */
    public function getPending(int $perPage = 15)
    {
        return VendorProfile::where('status', 'pending')
            ->with('user')
            ->paginate($perPage);
    }

    /**
     * Get approved vendors.
     */
    public function getApproved(int $perPage = 15)
    {
        return VendorProfile::where('status', 'approved')
            ->with('user')
            ->paginate($perPage);
    }
}
