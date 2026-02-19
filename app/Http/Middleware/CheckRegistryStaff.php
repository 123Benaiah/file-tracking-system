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

        if (!$user->is_registry_staff && !$user->isRegistryStaff()) {
            return redirect()->route('dashboard')
                ->with('toast', ['type' => 'error', 'title' => 'Access Denied', 'message' => 'Only Registry staff can access this area.']);
        }

        return $next($request);
    }
}
