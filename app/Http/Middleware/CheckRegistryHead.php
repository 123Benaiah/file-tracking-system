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

        if (!$user) {
            abort(403, 'Access denied.');
        }

        // Admins should use the admin panel
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->is_registry_head && !$user->isRegistryHead()) {
            return redirect()->route('dashboard')
                ->with('toast', ['type' => 'error', 'title' => 'Access Denied', 'message' => 'Only the Registry Head can perform this action.']);
        }

        return $next($request);
    }
}
