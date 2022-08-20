<?php

namespace App\Http\Middleware;

use Closure;

class OwnerMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()->hasRole($role)) {
            return response()->json( [
                'status' => 'false',
                'message' => 'you don’t have permission to access',
                'code' => 403,
            ], 403);
        }
        return $next($request);
    }
}
