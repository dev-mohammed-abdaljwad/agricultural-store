<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is authenticated and has admin role
        if (!$user || $user->role !== 'admin') {
            abort(403, 'غير مصرح بالوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
