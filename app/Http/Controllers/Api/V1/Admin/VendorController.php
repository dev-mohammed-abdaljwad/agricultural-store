<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveVendorRequest;
use App\Http\Resources\VendorResource;
use App\Services\VendorService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class VendorController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private VendorService $vendorService,
    ) {}

    /**
     * Get pending vendors.
     */
    public function pendingVendors(): JsonResponse
    {
        $vendors = $this->vendorService->getPendingVendors(15);

        return $this->paginatedResponse($vendors, 'Pending vendors retrieved successfully.');
    }

    /**
     * Get approved vendors.
     */
    public function approvedVendors(): JsonResponse
    {
        $vendors = $this->vendorService->getApprovedVendors(15);

        return $this->paginatedResponse($vendors, 'Approved vendors retrieved successfully.');
    }

    /**
     * Approve or reject vendor.
     */
    public function updateVendorStatus(int $vendorId, ApproveVendorRequest $request): JsonResponse
    {
        $status = $request->validated()['status'];

        if ($status === 'approved') {
            $this->vendorService->approveVendor($vendorId);
        } else {
            $this->vendorService->suspendVendor($vendorId);
        }

        $vendor = $this->vendorService->getVendorByUserId($vendorId);

        if (!$vendor) {
            return $this->errorResponse('Vendor not found.', 404);
        }

        return $this->successResponse(
            VendorResource::make($vendor),
            "Vendor {$status} successfully."
        );
    }
}
