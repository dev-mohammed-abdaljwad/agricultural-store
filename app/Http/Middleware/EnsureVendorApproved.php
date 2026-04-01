<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorApproved
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role === 'vendor') {
            $vendorProfile = $request->user()->vendorProfile;

            if (!$vendorProfile || $vendorProfile->status !== 'approved') {
                return $this->errorResponse('Your vendor account is not approved yet.', 403);
            }
        }

        return $next($request);
    }
}
