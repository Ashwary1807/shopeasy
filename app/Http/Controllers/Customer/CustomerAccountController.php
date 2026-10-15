<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerAccountController extends Controller
{
    /**
     * Display the authenticated customer's account dashboard overview.
     */
    public function dashboard(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        // Retrieve summary statistics strictly for this authenticated customer
        $ordersCount = $user->orders()->count();
        $recentOrders = $user->orders()->with('items')->latest('id')->take(3)->get();
        $wishlistCount = count(session('wishlist', []));

        return view('account.dashboard', compact('user', 'ordersCount', 'recentOrders', 'wishlistCount'));
    }

    /**
     * Display the authenticated customer's profile edit page.
     */
    public function profile(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('account.profile', compact('user'));
    }

    /**
     * Validate and update only the authenticated customer's personal details (name and email).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Server-side validation with email uniqueness ignoring only current user's ID
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Your full name must be at least 2 characters.',
            'name.max' => 'Your full name cannot exceed 100 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please provide a valid email format (e.g. name@example.com).',
            'email.unique' => 'This email address is already in use by another account.',
        ]);

        // 2. Safe normalization of inputs
        $cleanName = trim($validated['name']);
        $cleanEmail = strtolower(trim($validated['email']));

        // 3. Update scoped strictly to the authenticated user record
        $user->update([
            'name' => $cleanName,
            'email' => $cleanEmail,
        ]);

        return redirect()->route('account.profile')
            ->with('success', 'Your profile details have been updated successfully.');
    }
}
