<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate using employee_number instead of email
        $credentials = [
            'employee_number' => $request->employee_number,
            'password' => $request->password,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Log the login
            \App\Models\AuditLog::create([
                'employee_number' => Auth::user()->employee_number,
                'action' => 'login',
                'description' => 'User logged into the system',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended(route('dashboard', absolute: false));
        }

        return back()->withErrors([
            'employee_number' => 'Invalid credentials or account inactive.',
        ])->onlyInput('employee_number');
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Log the logout
        if (Auth::check()) {
            \App\Models\AuditLog::create([
                'employee_number' => Auth::user()->employee_number,
                'action' => 'logout',
                'description' => 'User logged out of the system',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
