<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Enforce active administrator session
        if (!$request->session()->has('admin_authenticated') || $request->session()->get('admin_authenticated') !== true) {
            return redirect()->route('admin.login')
                ->with('error', 'Administrator login required. Please authenticate to access the admin portal.');
        }

        return $next($request);
    }
}
