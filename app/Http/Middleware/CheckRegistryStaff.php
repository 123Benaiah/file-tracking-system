<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRegistryStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isRegistryStaff()) {
            abort(403, 'Access denied. Only Registry staff can access this area.');
        }

        return $next($request);
    }
}
