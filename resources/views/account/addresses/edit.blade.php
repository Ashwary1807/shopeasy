@extends('layouts.app')

@section('title', 'Edit Address - ShopEasy')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('account.index') }}" class="hover:text-brand-600 transition-colors">My Account</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('account.addresses.index') }}" class="hover:text-brand-600 transition-colors">Saved Addresses</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-brand-600 font-bold">Edit Address</span>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('account.addresses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors mb-2">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Back to Saved Addresses</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Edit Delivery Address
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Update recipient information and delivery coordinates for this location.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
            <form action="{{ route('account.addresses.update', $address->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Address Label Selection (Home, Work, Other) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                        Address Type / Label <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @php
                            $selectedLabel = old('label', $address->label);
                        @endphp

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="label"
                                value="Home"
                                class="peer sr-only"
                                {{ $selectedLabel === 'Home' ? 'checked' : '' }}
                            >
                            <div class="p-3 text-center rounded-2xl border border-slate-200 bg-slate-50 peer-checked:bg-brand-50 peer-checked:border-brand-500 peer-checked:text-brand-700 transition-all font-bold text-xs flex items-center justify-center gap-2">
                                <i class="fa-solid fa-house text-sm"></i>
                                <span>Home</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="label"
                                value="Work"
                                class="peer sr-only"
                                {{ $selectedLabel === 'Work' ? 'checked' : '' }}
                            >
                            <div class="p-3 text-center rounded-2xl border border-slate-200 bg-slate-50 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 transition-all font-bold text-xs flex items-center justify-center gap-2">
                                <i class="fa-solid fa-briefcase text-sm"></i>
                                <span>Work</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="label"
                                value="Other"
                                class="peer sr-only"
                                {{ $selectedLabel === 'Other' ? 'checked' : '' }}
                            >
                            <div class="p-3 text-center rounded-2xl border border-slate-200 bg-slate-50 peer-checked:bg-slate-100 peer-checked:border-slate-500 peer-checked:text-slate-800 transition-all font-bold text-xs flex items-center justify-center gap-2">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                                <span>Other</span>
                            </div>
                        </label>
                    </div>
                    @error('label')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Recipient Name -->
                    <div>
                        <label for="recipient_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Recipient Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="recipient_name"
                            name="recipient_name"
                            value="{{ old('recipient_name', $address->recipient_name) }}"
                            required
                            placeholder="e.g. Alex Johnson"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('recipient_name') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                        >
                        @error('recipient_name')
                            <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Phone Number <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $address->phone) }}"
                            required
                            placeholder="e.g. +91 98765 43210 or (555) 019-2834"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('phone') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                        >
                        @error('phone')
                            <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Street Address (Line 1) -->
                <div>
                    <label for="address_line1" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Street Address (Line 1) <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="address_line1"
                        name="address_line1"
                        value="{{ old('address_line1', $address->address_line1) }}"
                        required
                        placeholder="House number, street name, P.O. box"
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('address_line1') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                    >
                    @error('address_line1')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Line 2 (Optional) -->
                <div>
                    <label for="address_line2" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Apartment, Suite, Unit, Building <span class="text-slate-400 font-normal lowercase">(optional)</span>
                    </label>
                    <input
                        type="text"
                        id="address_line2"
                        name="address_line2"
                        value="{{ old('address_line2', $address->address_line2) }}"
                        placeholder="Apt 4B, 2nd Floor, etc."
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- City -->
                    <div>
                        <label for="city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            City <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            value="{{ old('city', $address->city) }}"
                            required
                            placeholder="e.g. Mumbai, New Delhi, or Springfield"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('city') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                        >
                        @error('city')
                            <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- State / Region -->
                    <div>
                        <label for="state" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            State / Province <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="state"
                            name="state"
                            value="{{ old('state', $address->state) }}"
                            required
                            placeholder="e.g. Maharashtra, Karnataka, or Oregon"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('state') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                        >
                        @error('state')
                            <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Postal Code -->
                    <div>
                        <label for="postal_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            ZIP / Postal Code <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="postal_code"
                            name="postal_code"
                            value="{{ old('postal_code', $address->postal_code) }}"
                            required
                            placeholder="e.g. 400001, 560001, or 97477"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('postal_code') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                        >
                        @error('postal_code')
                            <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Country <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="country"
                        name="country"
                        value="{{ old('country', $address->country) }}"
                        required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                    >
                    @error('country')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Default Address Checkbox -->
                <div class="pt-2">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_default"
                            value="1"
                            class="mt-0.5 w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300"
                            {{ old('is_default', $address->is_default) ? 'checked' : '' }}
                        >
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">
                                Make this my default delivery address
                            </span>
                            <span class="text-[11px] text-slate-400 block mt-0.5">
                                When selected, this address will be pre-selected during checkout.
                            </span>
                        </div>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <a
                        href="{{ route('account.addresses.index') }}"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors text-center"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="w-full sm:w-auto px-6 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold rounded-xl text-xs shadow-md hover:shadow transition-all inline-flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Update Address</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
