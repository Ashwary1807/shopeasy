<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // Save intended destination for seamless post-login redirect (Flipkart flow)
            if ($request->isMethod('GET')) {
                session()->put('url.intended', $request->fullUrl());
            } else {
                session()->put('url.intended', $request->is('checkout*') ? route('checkout.index') : route('account.index'));
            }

            $infoMessage = $request->is('checkout*')
                ? 'Please login to proceed with checkout.'
                : 'Please log in to access your account.';

            return redirect()->route('login')
                ->with('info', $infoMessage);
        }

        return $next($request);
    }
}
