<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Display the admin login form.
     */
    public function showLoginForm(): View
    {
        $configuredEmail = config('admin.email');
        $configuredPassword = config('admin.password');
        $isConfigured = !empty($configuredEmail) && !empty($configuredPassword);

        return view('admin.auth.login', compact('isConfigured'));
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Please enter your administrator email address.',
            'email.email' => 'Please enter a valid email format.',
            'password.required' => 'Please enter your administrator password.',
        ]);

        $configuredEmail = config('admin.email');
        $configuredPassword = config('admin.password');

        // Requirement 1: Fail securely if required environment variables are missing
        if (empty($configuredEmail) || empty($configuredPassword)) {
            return back()->withInput()->with('error', 'Admin portal authentication is not configured in the server environment. Please set ADMIN_EMAIL and ADMIN_PASSWORD in your .env file to enable access.');
        }

        $inputEmail = strtolower(trim((string) $request->input('email')));
        $inputPassword = (string) $request->input('password');

        // Constant-time comparison check for credentials
        $validEmail = hash_equals(strtolower(trim($configuredEmail)), $inputEmail);
        $validPassword = hash_equals((string) $configuredPassword, $inputPassword);

        if (!$validEmail || !$validPassword) {
            return back()->withInput($request->only('email'))->with('error', 'Invalid administrator email or password.');
        }

        // Requirement 1: Regenerate session after successful login
        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_email', $configuredEmail);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Logged in successfully to ShopEasy Admin Portal.');
    }

    /**
     * Terminate the admin session and log out.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Requirement 1: Invalidate the session and regenerate the CSRF token on logout
        $request->session()->forget(['admin_authenticated', 'admin_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been successfully logged out of the admin portal.');
    }
}
