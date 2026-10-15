<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    /**
     * Display the customer login form.
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Authenticate customer credentials and establish a secure session.
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|string',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please provide a valid email format (e.g. name@example.com).',
            'password.required' => 'Please enter your account password.',
        ]);

        $email = strtolower(trim($validated['email']));
        $password = (string) $validated['password'];
        $remember = $request->boolean('remember');

        if (!Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'These credentials do not match our records.',
                ]);
        }

        // Regenerate session ID for security while keeping cart and wishlist intact
        $request->session()->regenerate();

        $userName = Auth::user()->name;

        return redirect()->intended(route('home'))
            ->with('success', "Welcome back, {$userName}!");
    }

    /**
     * Display the customer registration form.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Register a new customer, hash password, auto-login, and redirect.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:150|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Your full name must be at least 2 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please provide a valid email format (e.g. name@example.com).',
            'email.unique' => 'An account with this email address already exists. Please log in instead.',
            'password.required' => 'Please choose a password.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
        ]);

        // Auto-login customer after registration
        Auth::login($user);

        // Regenerate session while keeping session cart intact
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Account created successfully! Welcome to ShopEasy.');
    }

    /**
     * Log out customer session, invalidate session, rotate CSRF, and preserve guest cart.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Safely preserve guest cart, wishlist, and admin session if present
        $cart = $request->session()->get('cart', []);
        $wishlist = $request->session()->get('wishlist', []);
        $adminAuthenticated = $request->session()->get('admin_authenticated');
        $adminEmail = $request->session()->get('admin_email');

        // Log out customer web guard
        Auth::guard('web')->logout();

        // Invalidate session and rotate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Restore cart, wishlist, and admin auth state
        if (!empty($cart)) {
            $request->session()->put('cart', $cart);
        }
        if (!empty($wishlist)) {
            $request->session()->put('wishlist', $wishlist);
        }
        if ($adminAuthenticated) {
            $request->session()->put('admin_authenticated', $adminAuthenticated);
            $request->session()->put('admin_email', $adminEmail);
        }

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }
}
