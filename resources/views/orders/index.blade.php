@extends('layouts.app')

@section('title', 'My Orders & Order Tracking - ShopEasy')

@section('content')

    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                @auth
                    <a href="{{ route('account.index') }}" class="hover:text-brand-600 transition-colors">My Account</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                @endauth
                <span class="text-brand-600 font-bold">My Orders &amp; Tracking</span>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Order Tracking &amp; History
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Track any order with your order details or view orders placed from this browser.
                </p>
            </div>

            <!-- 1. SECURE ORDER LOOKUP CARD (Requirement 4) -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-10">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Track an Order</h2>
                            <p class="text-xs text-slate-500">
                                Enter your Order Number and the matching Email Address used during checkout.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('orders.lookup') }}" method="POST" class="mt-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">

                            <!-- Order Number Input -->
                            <div class="sm:col-span-5">
                                <label for="order_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Order Number <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="order_number"
                                    name="order_number"
                                    value="{{ old('order_number') }}"
                                    placeholder="e.g. ORD-261013-ABCDEF"
                                    required
                                    class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('order_number') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-mono"
                                >
                                @error('order_number')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <!-- Customer Email Input -->
                            <div class="sm:col-span-5">
                                <label for="customer_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Customer Email <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="customer_email"
                                    name="customer_email"
                                    value="{{ old('customer_email') }}"
                                    placeholder="e.g. alex@example.com"
                                    required
                                    class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('customer_email') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all"
                                >
                                @error('customer_email')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="sm:col-span-2 flex items-end">
                                <button
                                    type="submit"
                                    class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow transition-all flex items-center justify-center gap-2 group h-[42px]"
                                >
                                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                    <span>Track</span>
                                </button>
                            </div>

                        </div>
                    </form>

                    <p class="text-[11px] text-slate-400 mt-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                        <span>For your privacy, both your Order Number and matching Email are required to look up order records.</span>
                    </p>
                </div>
            </div>

            <!-- 2. RECENT ORDERS IN THIS SESSION (Requirement 3 & 4) -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span>@auth My Order History @else Recent Orders in this Session @endauth</span>
                        @if($orders->total() > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-600 border border-brand-200">
                                {{ $orders->total() }}
                            </span>
                        @endif
                    </h2>
                </div>

                @if($orders->count() > 0)
                    <!-- Orders List Grid -->
                    <div class="space-y-4">
                        @foreach ($orders as $order)
                            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:border-brand-300 transition-all">

                                <div class="space-y-2 flex-1">
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <span class="text-sm font-extrabold text-slate-900 font-mono">
                                            {{ $order->order_number }}
                                        </span>

                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                            ];
                                            $badgeClass = $statusColors[strtolower($order->status)] ?? 'bg-slate-100 text-slate-800 border-slate-200';

                                            $paymentColors = [
                                                'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'failed' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                'refunded' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            ];
                                            $paymentBadgeClass = $paymentColors[strtolower($order->payment_status)] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $badgeClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $paymentBadgeClass }}">
                                            Payment: {{ ucfirst($order->payment_status) }}
                                        </span>

                                        <span class="text-xs text-slate-400">
                                            Placed on {{ $order->created_at->format('M d, Y') }}
                                        </span>
                                    </div>

                                    <div class="text-xs text-slate-600 flex flex-wrap items-center gap-y-1 gap-x-4">
                                        <span>
                                            <i class="fa-regular fa-user mr-1 text-slate-400"></i>{{ $order->customer_name }}
                                        </span>
                                        <span>
                                            <i class="fa-solid fa-box mr-1 text-slate-400"></i>{{ $order->items->count() }} item(s)
                                        </span>
                                        <span>
                                            <i class="fa-regular fa-credit-card mr-1 text-slate-400"></i>Cash on Delivery
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                    <div class="text-left sm:text-right">
                                        <span class="text-xs text-slate-400 block">Total Amount</span>
                                        <span class="text-lg font-extrabold text-slate-900">
                                            ${{ number_format((float)$order->total_amount, 2) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if(auth()->check() && $order->user_id && auth()->id() === $order->user_id && $order->status === 'pending')
                                            <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel Order #{{ $order->order_number }}? This will release reserved items and cannot be undone.');">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-700 border border-rose-200 font-semibold rounded-xl text-xs transition-colors inline-flex items-center gap-1.5"
                                                    title="Cancel this pending order"
                                                >
                                                    <i class="fa-solid fa-ban text-[11px]"></i>
                                                    <span class="hidden sm:inline">Cancel</span>
                                                </button>
                                            </form>
                                        @endif

                                        <a
                                            href="{{ route('orders.show', $order->order_number) }}"
                                            class="px-5 py-2.5 bg-slate-900 hover:bg-brand-600 active:bg-brand-700 text-white font-semibold rounded-xl text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm"
                                        >
                                            <span>View Details</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links (Requirement 4 & 11) -->
                    @if($orders->hasPages())
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif

                @else
                    <!-- Clean Empty State (Requirement 4 & 11) -->
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-10 sm:p-14 text-center max-w-xl mx-auto">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-2xl mb-4">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">@auth No Orders Placed Yet @else No Recent Orders in this Browser @endauth</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 mb-6 leading-relaxed">
                            @auth
                                You haven't placed any orders with this account yet. When you complete checkout while logged in, your orders will automatically appear here.
                            @else
                                Orders you place in this browser will automatically appear here. If you placed an order earlier or on another device, use the <strong>Track an Order</strong> form above to locate your order.
                            @endauth
                        </p>
                        <a
                            href="{{ route('shop.index') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors"
                        >
                            <i class="fa-solid fa-bag-shopping text-xs"></i>
                            <span>Browse Shop Catalog</span>
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>

@endsection
