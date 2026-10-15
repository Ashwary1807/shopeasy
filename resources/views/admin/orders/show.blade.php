@extends('layouts.admin')

@section('title', 'Manage Order #' . $order->order_number . ' - ShopEasy Admin')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Breadcrumbs & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.orders.index') }}" class="hover:text-brand-600 transition-colors">Orders</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-900 font-bold font-mono">#{{ $order->order_number }}</span>
            </nav>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('orders.show', $order->order_number) }}"
                    target="_blank"
                    class="px-3.5 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5 shadow-sm"
                    title="View how the customer sees this order"
                >
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>Customer Tracking View</span>
                </a>
                <a
                    href="{{ route('admin.orders.index') }}"
                    class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5 shadow-sm"
                >
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back to Orders</span>
                </a>
            </div>
        </div>

        <!-- Order Meta Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono">
                            Order #{{ $order->order_number }}
                        </h1>

                        <!-- Order Status Badge -->
                        @php
                            $orderBadge = match(strtolower($order->status)) {
                                'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                default => 'bg-amber-100 text-amber-800 border-amber-200',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $orderBadge }}">
                            Status: {{ ucfirst($order->status) }}
                        </span>

                        <!-- Payment Status Badge -->
                        @php
                            $paymentBadge = match(strtolower($order->payment_status)) {
                                'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'failed' => 'bg-rose-100 text-rose-800 border-rose-200',
                                default => 'bg-amber-100 text-amber-800 border-amber-200',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $paymentBadge }}">
                            Payment: {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-400 mt-1">
                        Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }} ({{ $order->created_at->diffForHumans() }})
                    </p>
                </div>

                <div class="text-left sm:text-right">
                    <span class="text-xs text-slate-400 block font-medium">Grand Total</span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 block">
                        ${{ number_format((float)$order->total_amount, 2) }}
                    </span>
                </div>
            </div>

            <!-- Quick Status Workflow Banner -->
            <div class="pt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600">
                    <strong class="font-bold text-slate-900 block mb-0.5">Order Fulfillment Flow:</strong>
                    <span>Pending &rarr; Processing (Packed) &rarr; Shipped (In Transit) &rarr; Delivered.</span>
                </div>
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 text-xs text-amber-900">
                    <strong class="font-bold text-amber-950 block mb-0.5">Cash on Delivery (COD) Rule:</strong>
                    <span>Marking payment as "Paid" signifies physical cash has been collected from the delivery courier.</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left Column: Order Items & Customer Details (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Ordered Line Items -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                    <h2 class="text-base font-bold text-slate-900 pb-4 mb-4 border-b border-slate-100 flex items-center justify-between">
                        <span>Purchased Items ({{ $order->items->count() }})</span>
                        <span class="text-xs text-slate-400 font-normal">Historical snapshot data</span>
                    </h2>

                    <div class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <div class="py-4 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                    @if($item->product && $item->product->image)
                                        <a href="{{ route('product.show', $item->product->id) }}" target="_blank" class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 block hover:opacity-80 transition-opacity">
                                            <img
                                                src="{{ $item->product->image }}"
                                                alt="{{ $item->product_name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        </a>
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
                                            ${{ number_format((float)$item->unit_price, 2) }} &times; {{ $item->quantity }} unit(s)
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

                    <!-- Items Cost Breakdown Subtotal -->
                    <div class="mt-6 pt-6 border-t border-slate-100 space-y-2 text-xs sm:text-sm text-slate-600">
                        <div class="flex items-center justify-between">
                            <span>Items Subtotal</span>
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
                        <div class="flex items-center justify-between">
                            <span>Shipping Charges</span>
                            @if((float)$order->shipping_charges == 0.0)
                                <span class="font-bold text-emerald-600 uppercase text-xs">FREE</span>
                            @else
                                <span class="font-bold text-slate-900">${{ number_format((float)$order->shipping_charges, 2) }}</span>
                            @endif
                        </div>
                        <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-base font-extrabold text-slate-900">
                            <span>Total Due / Paid</span>
                            <span class="text-xl text-brand-600 font-black">${{ number_format((float)$order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Details & Shipping Address -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                    <h2 class="text-base font-bold text-slate-900 pb-4 mb-4 border-b border-slate-100">
                        Customer &amp; Delivery Information
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs sm:text-sm">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Customer Profile</h3>
                            <p class="font-bold text-slate-900 text-sm">{{ $order->customer_name }}</p>
                            <p class="text-slate-600 mt-1"><i class="fa-regular fa-envelope mr-1.5 text-slate-400"></i>{{ $order->customer_email }}</p>
                            <p class="text-slate-600 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-slate-400"></i>{{ $order->customer_phone }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Delivery Address</h3>
                            <p class="text-slate-800 font-medium whitespace-pre-line leading-relaxed">
                                <i class="fa-solid fa-location-dot mr-1.5 text-rose-500"></i>{{ $order->delivery_address }}
                            </p>
                            @if(!empty($order->notes))
                                <div class="mt-2.5 p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                                    <strong>Delivery Notes:</strong> {{ $order->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Admin Status Updaters (4 Cols) -->
            <div class="lg:col-span-4 space-y-6 sticky top-24">

                <!-- 1. ORDER STATUS UPDATE FORM (Requirement 3) -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <i class="fa-solid fa-truck-ramp-box text-brand-600 text-base"></i>
                        <h2 class="text-sm font-bold text-slate-900">
                            Update Order Status
                        </h2>
                    </div>

                    <form action="{{ route('admin.orders.status', $order->order_number) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Fulfillment Stage
                            </label>
                            <select
                                id="status"
                                name="status"
                                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-semibold"
                            >
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Order Placed)</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Packaging)</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped (In Transit)</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered (Completed)</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <p class="text-[11px] text-slate-400">
                            Status changes instantly sync to the customer's live tracking timeline.
                        </p>

                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 bg-slate-900 hover:bg-brand-600 active:bg-brand-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center justify-center gap-2"
                        >
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Save Order Status</span>
                        </button>
                    </form>
                </div>

                <!-- 2. PAYMENT STATUS UPDATE FORM (Requirement 3) -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <i class="fa-regular fa-credit-card text-emerald-600 text-base"></i>
                        <h2 class="text-sm font-bold text-slate-900">
                            Update Payment Status
                        </h2>
                    </div>

                    <form action="{{ route('admin.orders.payment', $order->order_number) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="payment_status" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    Payment State
                                </label>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Mode: {{ $order->payment_method }}</span>
                            </div>
                            <select
                                id="payment_status"
                                name="payment_status"
                                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-semibold"
                            >
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending (Unpaid / COD Due)</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (Cash Collected)</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed (Rejected / Non-Payment)</option>
                            </select>
                            @error('payment_status')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-[11px] text-slate-500 leading-relaxed">
                            <i class="fa-solid fa-circle-exclamation text-amber-500 mr-1"></i>
                            For COD orders, marking as <strong>Paid</strong> confirms that physical cash has been collected from the recipient.
                        </div>

                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center justify-center gap-2"
                        >
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Save Payment Status</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>

@endsection
