<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartmentAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->is_active) {
            abort(403, 'Your account is inactive. Contact administrator.');
        }

        // Admins should use the admin panel, not the department dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Registry head should use the registry dashboard
        if ($user->isRegistryHead()) {
            return redirect()->route('registry.dashboard');
        }

        return $next($request);
    }
}
