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

        if (!$user) {
            abort(403, 'Access denied.');
        }

        // Admins should use the admin panel, not the registry dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Check is_registry_staff flag first, then fallback to method
        if (!$user->is_registry_staff && !$user->isRegistryStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Only Registry staff can access this area.');
        }

        return $next($request);
    }
}
