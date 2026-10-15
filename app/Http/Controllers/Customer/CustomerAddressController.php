<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerAddressController extends Controller
{
    /**
     * Display a listing of the customer's saved delivery addresses.
     */
    public function index(Request $request): View
    {
        $addresses = $request->user()->addresses()
            ->orderByDesc('is_default')
            ->latest('id')
            ->get();

        $canAddMore = $addresses->count() < 10;
        $maxLimit = 10;

        return view('account.addresses.index', compact('addresses', 'canAddMore', 'maxLimit'));
    }

    /**
     * Show the form for creating a new delivery address.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->addresses()->count() >= 10) {
            return redirect()->route('account.addresses.index')
                ->with('error', 'You have reached the maximum limit of 10 saved addresses. Please edit or delete an existing address.');
        }

        $isFirstAddress = $request->user()->addresses()->count() === 0;

        return view('account.addresses.create', compact('isFirstAddress'));
    }

    /**
     * Store a newly created delivery address for the authenticated customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Enforce 10-address limit server-side
        if ($user->addresses()->count() >= 10) {
            return redirect()->route('account.addresses.index')
                ->with('error', 'You cannot save more than 10 addresses. Please delete an existing address first.');
        }

        // 2. Validate all address fields server-side
        $validated = $request->validate([
            'label' => 'required|string|in:Home,Work,Other',
            'recipient_name' => 'required|string|min:2|max:100',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:7|max:25',
            'address_line1' => 'required|string|min:5|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|min:2|max:100',
            'state' => 'required|string|min:2|max:100',
            'postal_code' => 'required|string|min:3|max:20',
            'country' => 'required|string|min:2|max:100',
            'is_default' => 'nullable|boolean',
        ], [
            'label.required' => 'Please select an address label.',
            'label.in' => 'Address label must be Home, Work, or Other.',
            'recipient_name.required' => 'Please enter the recipient full name.',
            'recipient_name.min' => 'Recipient name must be at least 2 characters.',
            'phone.required' => 'Please enter a contact phone number.',
            'phone.regex' => 'Please enter a valid phone number (digits, spaces, hyphens, plus sign).',
            'address_line1.required' => 'Please provide the street address (Line 1).',
            'address_line1.min' => 'Street address must be at least 5 characters.',
            'city.required' => 'Please enter the city.',
            'state.required' => 'Please enter the state or region.',
            'postal_code.required' => 'Please enter the postal / ZIP code.',
            'country.required' => 'Please enter the country.',
        ]);

        // 3. Atomically persist and manage default address status
        DB::transaction(function () use ($user, $validated) {
            $isFirst = $user->addresses()->count() === 0;
            $shouldBeDefault = $isFirst || !empty($validated['is_default']);

            if ($shouldBeDefault) {
                // Clear any existing default flags
                $user->addresses()->update(['is_default' => false]);
            }

            $user->addresses()->create([
                'label' => $validated['label'],
                'recipient_name' => trim($validated['recipient_name']),
                'phone' => trim($validated['phone']),
                'address_line1' => trim($validated['address_line1']),
                'address_line2' => !empty($validated['address_line2']) ? trim($validated['address_line2']) : null,
                'city' => trim($validated['city']),
                'state' => trim($validated['state']),
                'postal_code' => trim($validated['postal_code']),
                'country' => trim($validated['country']),
                'is_default' => $shouldBeDefault,
            ]);
        });

        return redirect()->route('account.addresses.index')
            ->with('success', 'New address added successfully.');
    }

    /**
     * Show the form for editing an existing delivery address.
     */
    public function edit(Request $request, int $id): View
    {
        // Strictly scoped to authenticated customer (prevents IDOR)
        $address = $request->user()->addresses()->findOrFail($id);

        return view('account.addresses.edit', compact('address'));
    }

    /**
     * Update the specified delivery address in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // Strictly scoped to authenticated customer
        /** @var UserAddress $address */
        $address = $request->user()->addresses()->findOrFail($id);
        $user = $request->user();

        $validated = $request->validate([
            'label' => 'required|string|in:Home,Work,Other',
            'recipient_name' => 'required|string|min:2|max:100',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:7|max:25',
            'address_line1' => 'required|string|min:5|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|min:2|max:100',
            'state' => 'required|string|min:2|max:100',
            'postal_code' => 'required|string|min:3|max:20',
            'country' => 'required|string|min:2|max:100',
            'is_default' => 'nullable|boolean',
        ], [
            'label.required' => 'Please select an address label.',
            'label.in' => 'Address label must be Home, Work, or Other.',
            'recipient_name.required' => 'Please enter the recipient full name.',
            'recipient_name.min' => 'Recipient name must be at least 2 characters.',
            'phone.required' => 'Please enter a contact phone number.',
            'phone.regex' => 'Please enter a valid phone number (digits, spaces, hyphens, plus sign).',
            'address_line1.required' => 'Please provide the street address (Line 1).',
            'address_line1.min' => 'Street address must be at least 5 characters.',
            'city.required' => 'Please enter the city.',
            'state.required' => 'Please enter the state or region.',
            'postal_code.required' => 'Please enter the postal / ZIP code.',
            'country.required' => 'Please enter the country.',
        ]);

        DB::transaction(function () use ($user, $address, $validated) {
            $makeDefault = !empty($validated['is_default']);

            if ($makeDefault) {
                // Remove default from all other addresses
                $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            } elseif ($address->is_default) {
                // If this was default and user unchecks default, promote next address if available
                $otherAddress = $user->addresses()->where('id', '!=', $address->id)->latest('id')->first();
                if ($otherAddress) {
                    $otherAddress->update(['is_default' => true]);
                } else {
                    // Only address must remain default
                    $makeDefault = true;
                }
            }

            $address->update([
                'label' => $validated['label'],
                'recipient_name' => trim($validated['recipient_name']),
                'phone' => trim($validated['phone']),
                'address_line1' => trim($validated['address_line1']),
                'address_line2' => !empty($validated['address_line2']) ? trim($validated['address_line2']) : null,
                'city' => trim($validated['city']),
                'state' => trim($validated['state']),
                'postal_code' => trim($validated['postal_code']),
                'country' => trim($validated['country']),
                'is_default' => $makeDefault,
            ]);
        });

        return redirect()->route('account.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified address from storage and maintain a valid default.
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        // Strictly scoped to authenticated customer
        /** @var UserAddress $address */
        $address = $request->user()->addresses()->findOrFail($id);
        $user = $request->user();

        DB::transaction(function () use ($user, $address) {
            $wasDefault = $address->is_default;
            $address->delete();

            // If the deleted address was the default, promote the most recently updated remaining address
            if ($wasDefault) {
                $nextDefault = $user->addresses()->latest('updated_at')->first();
                if ($nextDefault) {
                    $nextDefault->update(['is_default' => true]);
                }
            }
        });

        return redirect()->route('account.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }

    /**
     * Mark the specified address as the customer's default delivery address.
     */
    public function setDefault(Request $request, int $id): RedirectResponse
    {
        // Strictly scoped to authenticated customer
        /** @var UserAddress $address */
        $address = $request->user()->addresses()->findOrFail($id);
        $user = $request->user();

        if ($address->is_default) {
            return redirect()->route('account.addresses.index')
                ->with('info', 'This address is already set as your default.');
        }

        DB::transaction(function () use ($user, $address) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return redirect()->route('account.addresses.index')
            ->with('success', "Address ({$address->label}) is now set as your default delivery address.");
    }
}
