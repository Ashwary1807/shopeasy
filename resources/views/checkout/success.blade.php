@extends('layouts.app')

@section('title', 'Order Confirmation - ShopEasy')

@section('content')

    <div class="bg-slate-50 min-h-screen py-10 sm:py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('shop.index') }}" class="hover:text-brand-600 transition-colors">Shop</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-brand-600 font-bold">Order Confirmation</span>
            </nav>

            <!-- Success Celebration Banner -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 text-center mb-8">
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center text-3xl shadow-sm mb-5">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                    Order Successfully Placed
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-3">
                    Thank You for Your Order!
                </h1>
                <p class="text-sm text-slate-500 max-w-lg mx-auto mt-2">
                    We have received your order and our team has started processing it. A confirmation has been logged for delivery.
                </p>

                <!-- Order Reference Number Pill -->
                <div class="mt-6 inline-flex flex-col sm:flex-row items-center gap-2 p-3 sm:px-6 rounded-2xl bg-slate-50 border border-slate-200">
                    <span class="text-xs text-slate-500 font-medium">Your Unique Order Number:</span>
                    <span class="text-base sm:text-lg font-black tracking-wider text-brand-600 font-mono select-all">
                        {{ $order->order_number }}
                    </span>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden mb-8">

                <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Order Summary</h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-100 text-amber-800 border border-amber-200">
                            Status: {{ ucfirst($order->status) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-blue-100 text-blue-800 border border-blue-200">
                            {{ strtoupper($order->payment_method) }} (Pay on Delivery)
                        </span>
                    </div>
                </div>

                <!-- Customer Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 sm:p-8 bg-slate-50/60 border-b border-slate-100 text-xs sm:text-sm">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                            Customer Details
                        </h3>
                        <p class="font-bold text-slate-900 text-sm">{{ $order->customer_name }}</p>
                        <p class="text-slate-600 mt-1"><i class="fa-regular fa-envelope mr-1.5 text-slate-400"></i>{{ $order->customer_email }}</p>
                        <p class="text-slate-600 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-slate-400"></i>{{ $order->customer_phone }}</p>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                            Delivery Address
                        </h3>
                        <p class="text-slate-800 font-medium whitespace-pre-line leading-relaxed">
                            <i class="fa-solid fa-location-dot mr-1.5 text-rose-500"></i>{{ $order->delivery_address }}
                        </p>
                        @if(!empty($order->notes))
                            <div class="mt-2.5 p-2 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900">
                                <strong>Delivery Notes:</strong> {{ $order->notes }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Itemized Order Items Table -->
                <div class="p-6 sm:p-8">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                        Purchased Items ({{ $order->items->count() }})
                    </h3>

                    <div class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <div class="py-4 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                    @if($item->product && $item->product->image)
                                        <img
                                            src="{{ $item->product->image }}"
                                            alt="{{ $item->product_name }}"
                                            class="w-14 h-14 rounded-xl object-cover bg-slate-100 border border-slate-200 shrink-0"
                                        >
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shrink-0">
                                            <i class="fa-solid fa-box"></i>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-slate-900 truncate" title="{{ $item->product_name }}">
                                            {{ $item->product_name }}
                                        </h4>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            Unit Price: ${{ number_format((float)$item->unit_price, 2) }} &times; {{ $item->quantity }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="text-sm font-extrabold text-slate-900">
                                        ${{ number_format((float)$item->item_subtotal, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cost Breakdown -->
                    <div class="mt-6 pt-6 border-t border-slate-100 max-w-sm ml-auto space-y-2.5 text-xs sm:text-sm">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900">${{ number_format((float)$order->subtotal, 2) }}</span>
                        </div>
                        @if(!empty($order->discount_amount) && (float)$order->discount_amount > 0)
                            <div class="flex items-center justify-between text-emerald-600 font-semibold">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-tag text-xs"></i>
                                    <span>Coupon Discount ({{ $order->coupon_code }})</span>
                                </span>
                                <span class="font-bold">-${{ number_format((float)$order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Shipping</span>
                            @if((float)$order->shipping_charges == 0.0)
                                <span class="font-bold text-emerald-600 uppercase text-xs">FREE</span>
                            @else
                                <span class="font-bold text-slate-900">${{ number_format((float)$order->shipping_charges, 2) }}</span>
                            @endif
                        </div>
                        <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-base font-extrabold text-slate-900">
                            <span>Total Paid / Due on Delivery</span>
                            <span class="text-xl text-brand-600">${{ number_format((float)$order->total_amount, 2) }}</span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a
                    href="{{ route('orders.show', $order->order_number) }}"
                    class="w-full sm:w-auto px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-sm shadow-md transition-all text-center inline-flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-truck-fast text-sm"></i>
                    <span>Track Order Progress</span>
                </a>
                <a
                    href="{{ route('shop.index') }}"
                    class="w-full sm:w-auto px-8 py-3.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition-all text-center inline-flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-bag-shopping text-sm"></i>
                    <span>Continue Shopping</span>
                </a>
                <a
                    href="{{ route('home') }}"
                    class="w-full sm:w-auto px-8 py-3.5 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl text-sm border border-slate-200 transition-all text-center inline-flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-house text-sm"></i>
                    <span>Back to Homepage</span>
                </a>
            </div>

        </div>
    </div>

@endsection
