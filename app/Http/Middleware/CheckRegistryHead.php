<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRegistryHead
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isRegistryHead()) {
            abort(403, 'Access denied. Only Registry Head can perform this action.');
        }

        return $next($request);
    }
}
