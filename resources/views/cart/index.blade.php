@extends('layouts.app')

@section('title', 'Shopping Cart - ShopEasy')

@section('content')

    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('shop.index') }}" class="hover:text-brand-600 transition-colors">Shop</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-brand-600 font-bold">Shopping Cart</span>
            </nav>

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Shopping Cart</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Review your selected items before proceeding to checkout.
                    </p>
                </div>

                @if(!empty($cartItems))
                    <!-- Clear Cart Form with Confirmation (Requirement 5) -->
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire cart?');">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3.5 py-2 rounded-xl transition-colors inline-flex items-center gap-1.5"
                        >
                            <i class="fa-regular fa-trash-can text-xs"></i>
                            <span>Clear Entire Cart</span>
                        </button>
                    </form>
                @endif
            </div>

            @if(!empty($cartItems))
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- Left: Cart Items Table/List -->
                    <div class="lg:col-span-8 space-y-4">

                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                            <div class="divide-y divide-slate-100">
                                @foreach ($cartItems as $item)
                                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group">

                                        <!-- Product Image & Basic Info -->
                                        <div class="flex items-center gap-4 min-w-0 flex-1">
                                            <a href="{{ route('product.show', $item['id']) }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200/80 block">
                                                <img
                                                    src="{{ $item['image'] }}"
                                                    alt="{{ $item['name'] }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                >
                                            </a>
                                            <div class="min-w-0">
                                                <span class="text-[10px] uppercase tracking-wider font-extrabold text-brand-600 block mb-0.5">
                                                    {{ $item['category'] }}
                                                </span>
                                                <a href="{{ route('product.show', $item['id']) }}" class="hover:text-brand-600 transition-colors">
                                                    <h3 class="text-sm font-bold text-slate-900 truncate max-w-xs sm:max-w-sm">
                                                        {{ $item['name'] }}
                                                    </h3>
                                                </a>
                                                <div class="flex items-baseline gap-2 mt-1">
                                                    <span class="text-sm font-extrabold text-slate-900">
                                                        ${{ number_format($item['price'], 2) }}
                                                    </span>
                                                    @if(!empty($item['original_price']))
                                                        <span class="text-xs text-slate-400 line-through">
                                                            ${{ number_format($item['original_price'], 2) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-slate-400 mt-0.5 block">
                                                    {{ $item['stock'] }} units in stock
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Quantity Form & Item Subtotal -->
                                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">

                                            <!-- Quantity Stepper Form -->
                                            <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="flex items-center border border-slate-200 rounded-xl bg-slate-50 p-1">
                                                @csrf
                                                @method('PATCH')

                                                <!-- Decrement Button -->
                                                <button
                                                    type="submit"
                                                    name="quantity"
                                                    value="{{ max(1, $item['quantity'] - 1) }}"
                                                    class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-white rounded-lg transition-colors {{ $item['quantity'] <= 1 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                                    title="Decrease quantity"
                                                >
                                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                                </button>

                                                <!-- Current Quantity Display -->
                                                <span class="w-8 text-center text-xs font-bold text-slate-800">
                                                    {{ $item['quantity'] }}
                                                </span>

                                                <!-- Increment Button -->
                                                <button
                                                    type="submit"
                                                    name="quantity"
                                                    value="{{ min($item['stock'], $item['quantity'] + 1) }}"
                                                    class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-white rounded-lg transition-colors {{ $item['quantity'] >= $item['stock'] ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                    {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}
                                                    title="Increase quantity"
                                                >
                                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                                </button>
                                            </form>

                                            <!-- Item Subtotal -->
                                            <div class="text-right min-w-[5rem]">
                                                <span class="text-sm font-extrabold text-slate-900 block">
                                                    ${{ number_format($item['subtotal'], 2) }}
                                                </span>
                                                <span class="text-[10px] text-slate-400">
                                                    ${{ number_format($item['price'], 2) }} &times; {{ $item['quantity'] }}
                                                </span>
                                            </div>

                                            <!-- Remove Item Form -->
                                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="w-8 h-8 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors"
                                                    title="Remove item"
                                                >
                                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                                </button>
                                            </form>

                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Continue Shopping Link -->
                        <div class="pt-2">
                            <a
                                href="{{ route('shop.index') }}"
                                class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-brand-600 transition-colors"
                            >
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                                <span>Continue Shopping Products</span>
                            </a>
                        </div>

                    </div>

                    <!-- Right: Order Summary Sidebar -->
                    <div class="lg:col-span-4">
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-6 sticky top-28">

                            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">
                                Order Summary
                            </h2>

                            <!-- Free Shipping Progress -->
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                                @if($subtotal >= 49.0)
                                    <div class="flex items-center gap-2 text-emerald-700 font-semibold">
                                        <i class="fa-solid fa-truck-fast text-emerald-600"></i>
                                        <span>Congratulations! You qualify for <strong>FREE Shipping</strong>.</span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between gap-2 text-slate-600">
                                        <span>Add <strong>${{ number_format(49.0 - $subtotal, 2) }}</strong> more for <strong>FREE Shipping</strong>!</span>
                                    </div>
                                    <div class="w-full bg-slate-200 h-1.5 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-brand-600 h-full rounded-full" style="width: {{ min(100, ($subtotal / 49.0) * 100) }}%"></div>
                                    </div>
                                @endif
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
                                                class="px-2.5 py-1 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-100/60 rounded-lg transition-colors inline-flex items-center gap-1 cursor-pointer"
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
                            <div class="space-y-3 text-xs sm:text-sm text-slate-600">
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
                                    <span>Estimated Shipping</span>
                                    @if($shipping == 0.0)
                                        <span class="font-bold text-emerald-600 uppercase text-xs">FREE</span>
                                    @else
                                        <span class="font-bold text-slate-900">${{ number_format($shipping, 2) }}</span>
                                    @endif
                                </div>
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-base font-extrabold text-slate-900">
                                    <span>Total Amount</span>
                                    <span class="text-xl text-brand-600">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Checkout Action (Functional Phase D) -->
                            <a
                                href="{{ route('checkout.index') }}"
                                class="w-full py-4 px-6 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group text-center"
                            >
                                <i class="fa-solid fa-lock text-xs group-hover:scale-110 transition-transform"></i>
                                <span>Proceed to Checkout</span>
                            </a>

                            <p class="text-[11px] text-center text-slate-400">
                                <i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> 256-Bit SSL Encrypted Checkout
                            </p>

                        </div>
                    </div>

                </div>
            @else
                <!-- Clean Empty State (Requirement 5) -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-12 sm:p-16 text-center max-w-2xl mx-auto my-6">
                    <div class="w-20 h-20 rounded-3xl bg-brand-50 text-brand-600 mx-auto flex items-center justify-center text-3xl shadow-sm mb-5">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Your Shopping Cart is Empty</h2>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 mb-8 leading-relaxed">
                        Looks like you haven't added any products to your cart yet. Explore our curated selection of gadgets, fashion, and lifestyle essentials!
                    </p>
                    <a
                        href="{{ route('shop.index') }}"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition-all"
                    >
                        <i class="fa-solid fa-bag-shopping text-sm"></i>
                        <span>Start Shopping Products</span>
                    </a>

                    @if(isset($availableCoupons) && $availableCoupons->isNotEmpty())
                        <div class="mt-10 pt-8 border-t border-slate-100 text-left max-w-lg mx-auto">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-tags text-brand-600"></i>
                                    <span>Active Store Coupons</span>
                                </span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $availableCoupons->count() }} offers available</span>
                            </div>
                            <div class="space-y-2">
                                @foreach($availableCoupons as $c)
                                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-slate-900 px-2 py-0.5 rounded bg-white border border-slate-200">
                                                    {{ $c->code }}
                                                </span>
                                                <span class="font-extrabold text-[10px] px-1.5 py-0.5 rounded bg-brand-100 text-brand-800">
                                                    {{ $c->discount_badge }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-1 truncate">
                                                {{ $c->terms_summary }}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            onclick="copyPromoCode('{{ $c->code }}')"
                                            class="px-2.5 py-1 text-xs font-bold bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-lg shadow-2xs transition-colors shrink-0 cursor-pointer"
                                            title="Copy code"
                                        >
                                            Copy
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

@endsection
