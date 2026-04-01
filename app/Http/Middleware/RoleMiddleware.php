<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return $this->errorResponse('Unauthenticated.', 401);
        }

        if (!in_array($request->user()->role, $roles)) {
            return $this->errorResponse('Unauthorized. You do not have the required role.', 403);
        }

        return $next($request);
    }
}
