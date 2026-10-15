@extends('layouts.app')

@section('title', 'My Account - ShopEasy')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-brand-600 font-bold">My Account</span>
        </nav>

        <!-- Welcome Banner & Header -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white text-2xl font-extrabold shadow-md shadow-brand-500/25 shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                                {{ $user->name }}
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> Active Customer
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1 flex items-center gap-2">
                            <span><i class="fa-regular fa-envelope mr-1 text-slate-400"></i>{{ $user->email }}</span>
                            <span class="text-slate-300">•</span>
                            <span><i class="fa-regular fa-calendar mr-1 text-slate-400"></i>Member since {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Recent' }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a
                        href="{{ route('account.profile') }}"
                        class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors inline-flex items-center gap-2"
                    >
                        <i class="fa-regular fa-id-badge text-slate-500"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a
                        href="{{ route('account.addresses.index') }}"
                        class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors inline-flex items-center gap-2"
                    >
                        <i class="fa-solid fa-map-location-dot text-amber-500"></i>
                        <span>Saved Addresses</span>
                    </a>
                    <a
                        href="{{ route('orders.index') }}"
                        class="px-3.5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl transition-colors shadow-sm inline-flex items-center gap-2"
                    >
                        <i class="fa-solid fa-box-open"></i>
                        <span>My Orders</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <!-- Orders Stat Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 hover:border-brand-300 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Orders</span>
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900 mb-1">
                    {{ $ordersCount }}
                </div>
                <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1">
                    <span>View all orders</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Wishlist Stat Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 hover:border-rose-300 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Saved in Wishlist</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-base">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900 mb-1">
                    {{ $wishlistCount }}
                </div>
                <a href="{{ route('wishlist.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700 inline-flex items-center gap-1">
                    <span>View saved items</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Profile Overview Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 hover:border-indigo-300 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Account Profile</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </div>
                <div class="text-sm font-bold text-slate-900 truncate mb-1">
                    {{ $user->email }}
                </div>
                <a href="{{ route('account.profile') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1">
                    <span>Update profile details</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Recent Orders Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Recent Orders</h2>
                    <p class="text-xs text-slate-500">Your latest purchases and their fulfillment progress.</p>
                </div>
                @if($ordersCount > 0)
                    <a href="{{ route('orders.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1">
                        <span>View All ({{ $ordersCount }})</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                @endif
            </div>

            @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
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
                                        {{ $order->created_at ? $order->created_at->format('M d, Y') : '' }}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-600 flex flex-wrap items-center gap-y-1 gap-x-4">
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

                                <a
                                    href="{{ route('orders.show', $order->order_number) }}"
                                    class="px-5 py-2.5 bg-slate-900 hover:bg-brand-600 text-white font-semibold rounded-xl text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm"
                                >
                                    <span>View Details</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <!-- Graceful Empty Orders State -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-10 sm:p-14 text-center max-w-xl mx-auto">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-2xl mb-4">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No Orders Placed Yet</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5 mb-6 leading-relaxed">
                        You have not placed any orders with your account yet. Explore our curated catalog of electronics, fashion, and home goods!
                    </p>
                    <a
                        href="{{ route('shop.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors"
                    >
                        <i class="fa-solid fa-bag-shopping text-xs"></i>
                        <span>Start Shopping Products</span>
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
