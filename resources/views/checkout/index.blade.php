@extends('layouts.app')

@section('title', 'Checkout - ShopEasy')

@section('content')

    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('shop.index') }}" class="hover:text-brand-600 transition-colors">Shop</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('cart.index') }}" class="hover:text-brand-600 transition-colors">Shopping Cart</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-brand-600 font-bold">Checkout</span>
            </nav>

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Checkout</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Provide your delivery information below to confirm and place your order.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left Column: Checkout Form (8 Cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" class="space-y-6">
                        @csrf

                        <!-- Authenticated Customer Banner (Flipkart Style) -->
                        <div class="bg-brand-50/70 border border-brand-200/80 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-brand-600 font-bold uppercase tracking-wider">Logged In Account</p>
                                    <p class="text-sm font-extrabold text-slate-900">{{ auth()->user()->name }} <span class="text-xs font-normal text-slate-500">({{ auth()->user()->email }})</span></p>
                                </div>
                            </div>
                            <span class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <i class="fa-solid fa-check text-[10px]"></i> Verified Customer
                            </span>
                        </div>

                        <!-- Card 1: Customer Contact Information -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
                                    1
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Contact Information</h2>
                                    <p class="text-xs text-slate-500">We will use this email to send your order confirmation and digital receipts.</p>
                                </div>
                            </div>

                            <div>
                                <label for="customer_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Email Address <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="customer_email"
                                    name="customer_email"
                                    value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                    placeholder="alex@example.com"
                                    required
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border @error('customer_email') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                >
                                @error('customer_email')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Card 2: Delivery Address -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Delivery Address</h2>
                                    <p class="text-xs text-slate-500">Select where you want your package delivered or provide a new location.</p>
                                </div>
                            </div>

                            @php
                                $hasSaved = $savedAddresses->isNotEmpty();
                                $currentSelected = old('selected_address_id', $hasSaved ? (string)$defaultAddressId : 'new');
                                $showNewForm = !$hasSaved
                                    || (string)$currentSelected === 'new'
                                    || $errors->has('new_label')
                                    || $errors->has('new_recipient_name')
                                    || $errors->has('new_phone')
                                    || $errors->has('new_address_line1')
                                    || $errors->has('new_city')
                                    || $errors->has('new_state')
                                    || $errors->has('new_postal_code')
                                    || $errors->has('new_country');
                            @endphp

                            @error('selected_address_id')
                                <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                            @if($hasSaved)
                                <!-- Saved Addresses List -->
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Saved Delivery Addresses ({{ $savedAddresses->count() }})
                                        </span>
                                        <a href="{{ route('account.addresses.index') }}" target="_blank" class="text-xs text-brand-600 hover:text-brand-700 font-semibold inline-flex items-center gap-1">
                                            <span>Manage Address Book</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach($savedAddresses as $address)
                                            @php
                                                $isSelected = (string)$currentSelected === (string)$address->id;
                                            @endphp
                                            <label class="relative block p-4 sm:p-5 rounded-2xl border transition-all cursor-pointer address-card-option {{ $isSelected ? 'border-brand-500 bg-brand-50/20 ring-2 ring-brand-500/20' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                                                <div class="flex items-start gap-3">
                                                    <input
                                                        type="radio"
                                                        name="selected_address_id"
                                                        value="{{ $address->id }}"
                                                        class="mt-1 w-4 h-4 text-brand-600 border-slate-300 focus:ring-brand-500 address-selection-radio"
                                                        {{ $isSelected ? 'checked' : '' }}
                                                    >
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span class="text-sm font-bold text-slate-900">{{ $address->recipient_name }}</span>

                                                                @if($address->label === 'Home')
                                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                                                        <i class="fa-solid fa-house mr-1 text-[9px]"></i>Home
                                                                    </span>
                                                                @elseif($address->label === 'Work')
                                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                                        <i class="fa-solid fa-briefcase mr-1 text-[9px]"></i>Work
                                                                    </span>
                                                                @else
                                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                                        <i class="fa-solid fa-location-dot mr-1 text-[9px]"></i>Other
                                                                    </span>
                                                                @endif

                                                                @if($address->is_default)
                                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                                        DEFAULT
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <span class="text-xs font-semibold text-slate-600 shrink-0">
                                                                <i class="fa-solid fa-phone text-[10px] mr-1 text-slate-400"></i>{{ $address->phone }}
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-slate-600 leading-relaxed">
                                                            {{ $address->formatted_address }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach

                                        <!-- Option to Deliver to a New Address -->
                                        @php
                                            $isNewSelected = (string)$currentSelected === 'new';
                                        @endphp
                                        <label class="relative block p-4 sm:p-5 rounded-2xl border transition-all cursor-pointer address-card-option {{ $isNewSelected ? 'border-brand-500 bg-brand-50/20 ring-2 ring-brand-500/20' : 'border-dashed border-slate-300 hover:border-slate-400 bg-slate-50/50' }}">
                                            <div class="flex items-start gap-3">
                                                <input
                                                    type="radio"
                                                    name="selected_address_id"
                                                    value="new"
                                                    class="mt-1 w-4 h-4 text-brand-600 border-slate-300 focus:ring-brand-500 address-selection-radio"
                                                    {{ $isNewSelected ? 'checked' : '' }}
                                                >
                                                <div class="flex-1">
                                                    <span class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                                                        <i class="fa-solid fa-plus text-xs text-brand-600"></i>
                                                        <span>Deliver to a New Address</span>
                                                    </span>
                                                    <p class="text-xs text-slate-500 mt-0.5">
                                                        Enter a different delivery recipient and location for this order.
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="selected_address_id" value="new">
                                <div class="mb-4 p-3.5 rounded-2xl bg-brand-50/60 border border-brand-200 text-xs text-brand-800 flex items-center gap-2.5">
                                    <i class="fa-solid fa-map-location-dot text-brand-600 text-sm"></i>
                                    <span>Please enter your delivery address below. You can save it to your account for faster future checkouts.</span>
                                </div>
                            @endif

                            <!-- Structured New Address Form Container -->
                            <div id="newAddressFormContainer" class="{{ $showNewForm ? '' : 'hidden' }} space-y-5 {{ $hasSaved ? 'mt-6 pt-6 border-t border-slate-200' : '' }}">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 mb-1">
                                        {{ $hasSaved ? 'New Address Details' : 'Delivery Address Details' }}
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Provide the complete recipient and shipping location details.
                                    </p>
                                </div>

                                <!-- Label Radio Selection -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Address Type / Label <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-3 gap-3">
                                        @php
                                            $newLabel = old('new_label', 'Home');
                                        @endphp
                                        <label class="cursor-pointer">
                                            <input type="radio" name="new_label" value="Home" class="peer sr-only" {{ $newLabel === 'Home' ? 'checked' : '' }}>
                                            <div class="p-2.5 text-center rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-brand-50 peer-checked:border-brand-500 peer-checked:text-brand-700 transition-all font-bold text-xs flex items-center justify-center gap-1.5">
                                                <i class="fa-solid fa-house text-xs"></i>
                                                <span>Home</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="new_label" value="Work" class="peer sr-only" {{ $newLabel === 'Work' ? 'checked' : '' }}>
                                            <div class="p-2.5 text-center rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 transition-all font-bold text-xs flex items-center justify-center gap-1.5">
                                                <i class="fa-solid fa-briefcase text-xs"></i>
                                                <span>Work</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="new_label" value="Other" class="peer sr-only" {{ $newLabel === 'Other' ? 'checked' : '' }}>
                                            <div class="p-2.5 text-center rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-slate-200 peer-checked:border-slate-500 peer-checked:text-slate-800 transition-all font-bold text-xs flex items-center justify-center gap-1.5">
                                                <i class="fa-solid fa-location-dot text-xs"></i>
                                                <span>Other</span>
                                            </div>
                                        </label>
                                    </div>
                                    @error('new_label')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <!-- Recipient Name & Phone -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="new_recipient_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Recipient Full Name <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="new_recipient_name"
                                            name="new_recipient_name"
                                            value="{{ old('new_recipient_name', auth()->user()->name ?? '') }}"
                                            placeholder="e.g. Alex Johnson"
                                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('new_recipient_name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                        >
                                        @error('new_recipient_name')
                                            <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="new_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Contact Phone <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="tel"
                                            id="new_phone"
                                            name="new_phone"
                                            value="{{ old('new_phone') }}"
                                            placeholder="e.g. +91 98765 43210 or (555) 019-2834"
                                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('new_phone') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                        >
                                        @error('new_phone')
                                            <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Street Address (Line 1) -->
                                <div>
                                    <label for="new_address_line1" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Street Address (Line 1) <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="new_address_line1"
                                        name="new_address_line1"
                                        value="{{ old('new_address_line1') }}"
                                        placeholder="House/flat number, building name, street name"
                                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('new_address_line1') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                    >
                                    @error('new_address_line1')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <!-- Address Line 2 (Optional) -->
                                <div>
                                    <label for="new_address_line2" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Apartment, Suite, Unit, Landmark <span class="text-slate-400 font-normal lowercase">(optional)</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="new_address_line2"
                                        name="new_address_line2"
                                        value="{{ old('new_address_line2') }}"
                                        placeholder="Apt 4B, 2nd Floor, near city park"
                                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                    >
                                </div>

                                <!-- City, State, Postal Code Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label for="new_city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            City <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="new_city"
                                            name="new_city"
                                            value="{{ old('new_city') }}"
                                            placeholder="e.g. Mumbai, Springfield"
                                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('new_city') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                        >
                                        @error('new_city')
                                            <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="new_state" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            State / Province <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="new_state"
                                            name="new_state"
                                            value="{{ old('new_state') }}"
                                            placeholder="e.g. Maharashtra, Oregon"
                                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('new_state') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                        >
                                        @error('new_state')
                                            <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="new_postal_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            ZIP / PIN Code <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="new_postal_code"
                                            name="new_postal_code"
                                            value="{{ old('new_postal_code') }}"
                                            placeholder="e.g. 400001 or 97477"
                                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('new_postal_code') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                        >
                                        @error('new_postal_code')
                                            <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Country -->
                                <div>
                                    <label for="new_country" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Country <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="new_country"
                                        name="new_country"
                                        value="{{ old('new_country', 'India') }}"
                                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('new_country') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                    >
                                    @error('new_country')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <!-- Save Address Checkbox -->
                                <div class="pt-2">
                                    @if($canSaveMoreAddresses)
                                        <label class="flex items-start gap-3 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="save_address"
                                                value="1"
                                                class="mt-0.5 w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300"
                                                {{ old('save_address', $savedAddresses->isEmpty() ? '1' : '') ? 'checked' : '' }}
                                            >
                                            <div>
                                                <span class="text-xs font-bold text-slate-900 block">
                                                    Save this address to My Account
                                                </span>
                                                <span class="text-[11px] text-slate-500 block mt-0.5">
                                                    @if($savedAddresses->isEmpty())
                                                        This will be saved as your default delivery address for future orders.
                                                    @else
                                                        Adds this location to your address book for one-click selection on future orders.
                                                    @endif
                                                </span>
                                            </div>
                                        </label>
                                    @else
                                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800">
                                            <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                                            <div>
                                                <span class="font-bold block">Maximum address limit reached (10/10)</span>
                                                <span class="text-[11px] text-amber-700 block mt-0.5">
                                                    You have 10 saved addresses. This address will be used for this order only without saving to your address book.
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Special Delivery Instructions (Optional) -->
                            <div class="mt-6 pt-5 border-t border-slate-100">
                                <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Order Notes &amp; Delivery Instructions <span class="text-slate-400 font-normal lowercase">(optional)</span>
                                </label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="2"
                                    placeholder="e.g. Please leave package at the front porch or call upon arrival."
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 font-medium"
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Card 3: Payment Method (Cash on Delivery) -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
                                    3
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Payment Method</h2>
                                    <p class="text-xs text-slate-500">Select how you would like to pay for this order.</p>
                                </div>
                            </div>

                            <div class="p-4 sm:p-5 rounded-2xl bg-brand-50/60 border border-brand-200 flex items-start gap-4">
                                <div class="mt-0.5">
                                    <input
                                        type="radio"
                                        id="payment_cod"
                                        name="payment_method_display"
                                        value="cod"
                                        checked
                                        disabled
                                        class="w-4 h-4 text-brand-600 border-slate-300 focus:ring-brand-500"
                                    >
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <label for="payment_cod" class="text-sm font-bold text-slate-900 cursor-pointer">
                                            Cash on Delivery (COD)
                                        </label>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            No Advance Payment
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                        Pay with cash when your package is delivered to your doorstep. Inspect your items before making payment.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100 text-[11px] text-slate-500 flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-brand-500 text-xs"></i>
                                <span>Card and digital payments will be connected in future updates.</span>
                            </div>
                        </div>

                        <!-- Submit Order Button -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full py-4 px-6 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold rounded-2xl text-base shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3 group"
                            >
                                <i class="fa-solid fa-lock text-sm group-hover:scale-110 transition-transform"></i>
                                <span>Confirm &amp; Place Order (${{ number_format($total, 2) }})</span>
                            </button>
                            <p class="text-[11px] text-center text-slate-400 mt-2.5">
                                By placing this order, you confirm that you agree to ShopEasy's Terms of Service and Return Policy.
                            </p>
                        </div>

                    </form>
                </div>

                <!-- Right Column: Order Review Sidebar (4 Cols) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-6 sticky top-28">

                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h2 class="text-lg font-bold text-slate-900">
                                Order Summary
                            </h2>
                            <a href="{{ route('cart.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                Edit Cart
                            </a>
                        </div>

                        <!-- Itemized Cart Items Preview -->
                        <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto pr-1">
                            @foreach ($cartItems as $item)
                                <div class="py-3 flex items-center gap-3 first:pt-0 last:pb-0">
                                    <img
                                        src="{{ $item['image'] }}"
                                        alt="{{ $item['name'] }}"
                                        class="w-12 h-12 rounded-xl object-cover bg-slate-100 border border-slate-200 shrink-0"
                                    >
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-slate-800 truncate" title="{{ $item['name'] }}">
                                            {{ $item['name'] }}
                                        </h4>
                                        <p class="text-[11px] text-slate-400">
                                            Qty: {{ $item['quantity'] }} &times; ${{ number_format($item['price'], 2) }}
                                        </p>
                                    </div>
                                    <span class="text-xs font-extrabold text-slate-900">
                                        ${{ number_format($item['subtotal'], 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Coupon / Promo Code Module (Phase 5) -->
                        <div class="pt-4 border-t border-slate-100">
                            @if(!empty($appliedCoupon))
                                <!-- Applied Coupon Card -->
                                <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200/90 flex items-center justify-between gap-3 shadow-sm">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-xs shrink-0 shadow-sm">
                                            <i class="fa-solid fa-tag"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono font-black text-xs text-emerald-900 tracking-wider uppercase">
                                                    {{ $appliedCoupon->code }}
                                                </span>
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-emerald-200/80 text-emerald-900">
                                                    APPLIED
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-emerald-700 font-medium truncate mt-0.5">
                                                Saved ${{ number_format($discount, 2) }} on your order
                                            </p>
                                        </div>
                                    </div>
                                    <form action="{{ route('checkout.coupon.remove') }}" method="POST" class="shrink-0">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-2.5 py-1 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-100/60 rounded-lg transition-colors inline-flex items-center gap-1"
                                            title="Remove coupon"
                                        >
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                            <span>Remove</span>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Apply Coupon Form -->
                                <form action="{{ route('checkout.coupon.apply') }}" method="POST" class="space-y-2">
                                    @csrf
                                    <label for="coupon_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Have a Promo Code?
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <i class="fa-solid fa-ticket absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                            <input
                                                type="text"
                                                id="coupon_code"
                                                name="coupon_code"
                                                placeholder="e.g. SHOPEASY50"
                                                value="{{ old('coupon_code') }}"
                                                class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl uppercase font-mono font-bold focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 tracking-wider placeholder:normal-case placeholder:font-sans placeholder:font-normal"
                                                required
                                            >
                                        </div>
                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-slate-900 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl transition-colors shadow-sm shrink-0 cursor-pointer"
                                        >
                                            Apply
                                        </button>
                                    </div>
                                    @error('coupon_code')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </form>
                            @endif

                            @if(isset($availableCoupons) && $availableCoupons->isNotEmpty())
                                <!-- Available Offers Module -->
                                <div class="mt-3 pt-3 border-t border-slate-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fa-solid fa-tags text-brand-600 text-[10px]"></i>
                                            <span>Available Offers</span>
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $availableCoupons->count() }} active</span>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($availableCoupons as $availCoupon)
                                            @php
                                                $isThisApplied = !empty($appliedCoupon) && $appliedCoupon->code === $availCoupon->code;
                                            @endphp
                                            <div class="p-2.5 rounded-xl border {{ $isThisApplied ? 'bg-emerald-50/60 border-emerald-200' : 'bg-slate-50/80 border-slate-200/90' }} transition-all flex flex-col gap-1.5 text-xs">
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="font-mono font-bold text-slate-800 tracking-wider text-[11px] px-1.5 py-0.5 rounded bg-white border border-slate-200 shadow-2xs">
                                                            {{ $availCoupon->code }}
                                                        </span>
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-brand-100 text-brand-800">
                                                            {{ $availCoupon->discount_badge }}
                                                        </span>
                                                    </div>
                                                    @if($isThisApplied)
                                                        <span class="text-[10px] font-bold text-emerald-700 flex items-center gap-1">
                                                            <i class="fa-solid fa-check text-[9px]"></i> Applied
                                                        </span>
                                                    @else
                                                        <form action="{{ route('checkout.coupon.apply') }}" method="POST" class="shrink-0">
                                                            @csrf
                                                            <input type="hidden" name="coupon_code" value="{{ $availCoupon->code }}">
                                                            <button
                                                                type="submit"
                                                                class="px-2.5 py-1 bg-white hover:bg-brand-50 text-brand-600 hover:text-brand-700 border border-brand-200 hover:border-brand-300 font-bold text-[11px] rounded-lg shadow-2xs transition-colors cursor-pointer"
                                                                title="Apply {{ $availCoupon->code }}"
                                                            >
                                                                Apply
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] text-slate-500 leading-snug">
                                                    {{ $availCoupon->terms_summary }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="space-y-3 pt-3 border-t border-slate-100 text-xs sm:text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal ({{ $totalQuantity }} items)</span>
                                <span class="font-bold text-slate-900">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if(isset($discount) && $discount > 0)
                                <div class="flex items-center justify-between text-emerald-600 font-semibold">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-tag text-xs"></i>
                                        <span>Coupon Discount ({{ $appliedCoupon ? $appliedCoupon->code : session('applied_coupon.code') }})</span>
                                    </span>
                                    <span class="font-bold">-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span>Shipping Charges</span>
                                @if($shipping == 0.0)
                                    <span class="font-bold text-emerald-600 uppercase text-xs">FREE</span>
                                @else
                                    <span class="font-bold text-slate-900">${{ number_format($shipping, 2) }}</span>
                                @endif
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-base font-extrabold text-slate-900">
                                <span>Total Amount</span>
                                <span class="text-2xl text-brand-600 font-black">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Guarantees Strip -->
                        <div class="pt-3 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-truck-fast text-brand-600 text-xs w-4"></i>
                                <span>Free shipping on all orders over $49</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-emerald-600 text-xs w-4"></i>
                                <span>100% verified authentic products</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-handshake-simple text-amber-500 text-xs w-4"></i>
                                <span>Pay on delivery with cash</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addressRadios = document.querySelectorAll('.address-selection-radio');
            const newAddressContainer = document.getElementById('newAddressFormContainer');
            const addressCards = document.querySelectorAll('.address-card-option');

            function updateAddressUI() {
                const selectedRadio = document.querySelector('.address-selection-radio:checked');
                if (!selectedRadio || !newAddressContainer) return;

                if (selectedRadio.value === 'new') {
                    newAddressContainer.classList.remove('hidden');
                } else {
                    newAddressContainer.classList.add('hidden');
                }

                addressCards.forEach(card => {
                    const radio = card.querySelector('.address-selection-radio');
                    if (radio && radio.checked) {
                        card.classList.add('border-brand-500', 'bg-brand-50/20', 'ring-2', 'ring-brand-500/20');
                        card.classList.remove('border-slate-200', 'border-dashed', 'border-slate-300', 'hover:border-slate-300', 'bg-slate-50/50');
                    } else if (radio) {
                        card.classList.remove('border-brand-500', 'bg-brand-50/20', 'ring-2', 'ring-brand-500/20');
                        if (radio.value === 'new') {
                            card.classList.add('border-dashed', 'border-slate-300', 'bg-slate-50/50');
                        } else {
                            card.classList.add('border-slate-200', 'bg-white');
                        }
                    }
                });
            }

            addressRadios.forEach(radio => {
                radio.addEventListener('change', updateAddressUI);
            });
        });
    </script>

@endsection
