@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - ShopEasy')

@section('content')

    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('orders.index') }}" class="hover:text-brand-600 transition-colors">My Orders</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-brand-600 font-bold font-mono">#{{ $order->order_number }}</span>
            </nav>

            <!-- Order Header Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono">
                                Order #{{ $order->order_number }}
                            </h1>

                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                ];
                                $currentStatus = strtolower($order->status);
                                $badgeClass = $statusColors[$currentStatus] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $badgeClass }}">
                                Status: {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        @if(auth()->check() && $order->user_id && auth()->id() === $order->user_id && $order->status === 'pending')
                            <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel Order #{{ $order->order_number }}? This will release reserved items and cannot be undone.');" class="inline">
                                @csrf
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-700 border border-rose-200 text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-ban text-xs"></i>
                                    <span>Cancel Order</span>
                                </button>
                            </form>
                        @endif
                        <button
                            type="button"
                            onclick="window.print()"
                            class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5"
                        >
                            <i class="fa-solid fa-print text-xs"></i>
                            <span>Print Receipt</span>
                        </button>
                        <a
                            href="{{ route('orders.index') }}"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5"
                        >
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            <span>Back to Orders</span>
                        </a>
                    </div>
                </div>

                <!-- ORDER STATUS PROGRESS TIMELINE -->
                <div class="pt-8">
                    @if($currentStatus === 'cancelled')
                        <!-- Cancelled State (Separate Distinct Card) -->
                        <div class="p-6 rounded-3xl bg-rose-50 border border-rose-200 text-rose-900 shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-rose-200/80">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-xl shadow-sm shrink-0">
                                        <i class="fa-solid fa-ban"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-extrabold text-rose-900">This Order Has Been Cancelled</h3>
                                        <p class="text-xs text-rose-700 mt-0.5">
                                            This order is no longer in progress and will not be delivered.
                                        </p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-rose-200 text-rose-800 border border-rose-300 self-start sm:self-auto">
                                    Cancelled
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 text-xs">
                                <div>
                                    <span class="text-rose-600/80 block font-bold uppercase tracking-wider text-[10px]">Order Placement &amp; Update History</span>
                                    <p class="text-rose-900 mt-1.5 font-medium">
                                        <i class="fa-regular fa-calendar mr-1.5 text-rose-500"></i>Placed: <strong>{{ $order->created_at->format('M d, Y \a\t h:i A') }}</strong>
                                    </p>
                                    <p class="text-rose-900 mt-1 font-medium">
                                        <i class="fa-solid fa-clock-rotate-left mr-1.5 text-rose-500"></i>Last Updated / Status Modified: <strong>{{ $order->updated_at ? $order->updated_at->format('M d, Y \a\t h:i A') : 'N/A' }}</strong>
                                    </p>
                                </div>

                                <div>
                                    <span class="text-rose-600/80 block font-bold uppercase tracking-wider text-[10px]">Payment Settlement Details</span>
                                    <p class="text-rose-900 mt-1.5 font-medium leading-relaxed">
                                        @if(strtolower((string)$order->payment_method) === 'cod' && strtolower((string)$order->payment_status) === 'pending')
                                            <i class="fa-solid fa-shield-halved mr-1.5 text-emerald-600"></i>Mode: Cash on Delivery (COD). No payment was collected for this cancelled order.
                                        @elseif(strtolower((string)$order->payment_status) === 'paid')
                                            <i class="fa-solid fa-receipt mr-1.5 text-rose-600"></i>Recorded Payment Status: <strong>Paid</strong>. If eligible, our support team will initiate refund processing.
                                        @elseif(strtolower((string)$order->payment_status) === 'failed')
                                            <i class="fa-solid fa-circle-exclamation mr-1.5 text-rose-600"></i>Recorded Payment Status: <strong>Failed</strong>. No successful payment transaction was captured.
                                        @else
                                            <i class="fa-regular fa-credit-card mr-1.5 text-rose-600"></i>Payment Status: <strong>{{ ucfirst($order->payment_status) }}</strong> (Method: {{ strtoupper($order->payment_method) }}).
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-rose-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-rose-800">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-question text-rose-600"></i>
                                    <span>Need assistance? Contact customer support at <strong class="font-bold text-rose-950">+1 (800) 123-4567</strong>.</span>
                                </span>
                                <a href="{{ route('shop.index') }}" class="text-xs font-bold text-rose-700 hover:text-rose-900 inline-flex items-center gap-1 transition-colors">
                                    <span>Browse shop catalog</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        @php
                            // 4 Stage progression definitions in exact specified order
                            $stages = [
                                1 => [
                                    'key' => 'pending',
                                    'title' => 'Order Placed',
                                    'subtitle' => 'Pending Confirmation',
                                    'desc' => 'Your order has been registered and is waiting to be processed by fulfillment.',
                                    'icon' => 'fa-solid fa-receipt',
                                ],
                                2 => [
                                    'key' => 'processing',
                                    'title' => 'Preparing Your Order',
                                    'subtitle' => 'Processing & Packing',
                                    'desc' => 'Your items are being packed and readied for dispatch.',
                                    'icon' => 'fa-solid fa-box-open',
                                ],
                                3 => [
                                    'key' => 'shipped',
                                    'title' => 'On the Way',
                                    'subtitle' => 'Shipped in Transit',
                                    'desc' => 'Your package is on the way with our delivery partner.',
                                    'icon' => 'fa-solid fa-truck-fast',
                                ],
                                4 => [
                                    'key' => 'delivered',
                                    'title' => 'Successfully Delivered',
                                    'subtitle' => 'Completed Delivery',
                                    'desc' => 'Your order has been delivered successfully.',
                                    'icon' => 'fa-solid fa-house-chimney-check',
                                ],
                            ];

                            $stageLevel = match($currentStatus) {
                                'processing' => 2,
                                'shipped' => 3,
                                'delivered' => 4,
                                default => 1, // 'pending'
                            };

                            // Calculate progress bar percentage
                            $progressPercent = match($currentStatus) {
                                'processing' => 33.33,
                                'shipped' => 66.66,
                                'delivered' => 100,
                                default => 0, // 'pending'
                            };

                            $currentStageData = $stages[$stageLevel] ?? $stages[1];
                        @endphp

                        <!-- Desktop Horizontal Timeline (sm: and up) -->
                        <div class="hidden sm:block">
                            <div class="relative">
                                <!-- Background connecting progress track -->
                                <div class="absolute top-5 left-12 right-12 h-1 bg-slate-200 -z-0">
                                    <div
                                        class="h-full {{ $currentStatus === 'delivered' ? 'bg-emerald-600' : 'bg-brand-600' }} transition-all duration-700 ease-in-out"
                                        style="width: {{ $progressPercent }}%"
                                    ></div>
                                </div>

                                <!-- 4 Stage Milestones Grid -->
                                <div class="grid grid-cols-4 gap-4 relative z-10">
                                    @foreach($stages as $stepNum => $stage)
                                        @php
                                            $isCompleted = ($currentStatus === 'delivered') || ($stepNum < $stageLevel);
                                            $isCurrent = ($currentStatus !== 'delivered') && ($stepNum === $stageLevel);
                                            $isUpcoming = ($currentStatus !== 'delivered') && ($stepNum > $stageLevel);
                                        @endphp

                                        <div class="flex flex-col items-center text-center">
                                            <!-- Milestone Circle Node -->
                                            @if($isCompleted)
                                                <div class="w-10 h-10 rounded-full {{ $currentStatus === 'delivered' && $stepNum === 4 ? 'bg-emerald-600 ring-emerald-100' : 'bg-brand-600 ring-brand-100' }} text-white flex items-center justify-center font-bold text-sm shadow-md shrink-0 ring-4 transition-all">
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </div>
                                            @elseif($isCurrent)
                                                <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow-md shrink-0 ring-4 ring-brand-200 transition-all">
                                                    <i class="{{ $stage['icon'] }} text-xs"></i>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 border-2 border-slate-200 flex items-center justify-center font-bold text-xs shrink-0 transition-all">
                                                    <span>{{ $stepNum }}</span>
                                                </div>
                                            @endif

                                            <!-- Stage Labels -->
                                            <div class="mt-2.5">
                                                <span class="text-xs font-bold block {{ $isCompleted || $isCurrent ? 'text-slate-900' : 'text-slate-400' }}">
                                                    {{ $stage['title'] }}
                                                </span>

                                                <span class="text-[11px] block mt-0.5 {{ $isCurrent ? 'text-brand-600 font-bold' : ($isCompleted ? 'text-emerald-600 font-semibold' : 'text-slate-400') }}">
                                                    @if($isCurrent)
                                                        <span class="inline-flex items-center gap-1">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                                                            Current Stage
                                                        </span>
                                                    @elseif($isCompleted)
                                                        @if($stepNum === 1)
                                                            {{ $order->created_at->format('M d') }}
                                                        @else
                                                            Completed
                                                        @endif
                                                    @else
                                                        Upcoming
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Vertical Timeline (screen < sm) -->
                        <div class="sm:hidden space-y-0 pl-1">
                            @foreach($stages as $stepNum => $stage)
                                @php
                                    $isCompleted = ($currentStatus === 'delivered') || ($stepNum < $stageLevel);
                                    $isCurrent = ($currentStatus !== 'delivered') && ($stepNum === $stageLevel);
                                    $isUpcoming = ($currentStatus !== 'delivered') && ($stepNum > $stageLevel);
                                    $isLast = ($stepNum === 4);
                                @endphp

                                <div class="relative flex items-start gap-4 {{ $isLast ? '' : 'pb-6' }}">
                                    <!-- Vertical Connecting Line -->
                                    @if(!$isLast)
                                        <div class="absolute top-10 left-5 -ml-px w-0.5 h-full {{ $stepNum < $stageLevel || $currentStatus === 'delivered' ? ($currentStatus === 'delivered' ? 'bg-emerald-600' : 'bg-brand-600') : 'bg-slate-200' }}"></div>
                                    @endif

                                    <!-- Milestone Node -->
                                    @if($isCompleted)
                                        <div class="w-10 h-10 rounded-full {{ $currentStatus === 'delivered' && $stepNum === 4 ? 'bg-emerald-600 ring-emerald-100' : 'bg-brand-600 ring-brand-100' }} text-white flex items-center justify-center font-bold text-sm shadow-md shrink-0 ring-4 relative z-10">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </div>
                                    @elseif($isCurrent)
                                        <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow-md shrink-0 ring-4 ring-brand-200 relative z-10">
                                            <i class="{{ $stage['icon'] }} text-xs"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 border-2 border-slate-200 flex items-center justify-center font-bold text-xs shrink-0 relative z-10">
                                            <span>{{ $stepNum }}</span>
                                        </div>
                                    @endif

                                    <!-- Content -->
                                    <div class="pt-1 min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-bold block {{ $isCompleted || $isCurrent ? 'text-slate-900' : 'text-slate-400' }}">
                                                {{ $stage['title'] }}
                                            </span>

                                            @if($isCurrent)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-brand-50 text-brand-700 border border-brand-200 inline-flex items-center gap-1">
                                                    <span class="w-1 h-1 rounded-full bg-brand-600"></span>
                                                    Current
                                                </span>
                                            @elseif($isCompleted)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    {{ $stepNum === 1 ? $order->created_at->format('M d') : 'Completed' }}
                                                </span>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-medium">
                                                    Upcoming
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $stage['subtitle'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Current Stage Helper Notice Card -->
                        <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-700 flex items-start sm:items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-brand-600 shrink-0 shadow-xs mt-0.5 sm:mt-0">
                                <i class="{{ $currentStageData['icon'] }} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="font-bold text-slate-900 block mb-0.5">
                                    Current Fulfillment Stage: {{ $currentStageData['title'] }} ({{ ucfirst($currentStatus) }})
                                </span>
                                <span class="text-slate-500 leading-relaxed block">
                                    {{ $currentStageData['desc'] }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left: Items & Delivery Details (8 Cols) -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Ordered Items Card -->
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                        <h2 class="text-base font-bold text-slate-900 pb-4 mb-4 border-b border-slate-100 flex items-center justify-between">
                            <span>Ordered Items ({{ $order->items->count() }})</span>
                            <span class="text-xs text-slate-400 font-normal">Historical purchase snapshot</span>
                        </h2>

                        <div class="divide-y divide-slate-100">
                            @foreach ($order->items as $item)
                                <div class="py-4 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                        @if($item->product && $item->product->image)
                                            <a href="{{ route('product.show', $item->product->id) }}" class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 block hover:opacity-80 transition-opacity">
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
                    </div>

                    <!-- Customer & Delivery Address Card -->
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                        <h2 class="text-base font-bold text-slate-900 pb-4 mb-4 border-b border-slate-100">
                            Customer &amp; Delivery Information
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs sm:text-sm">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Recipient</h3>
                                <p class="font-bold text-slate-900">{{ $order->customer_name }}</p>
                                <p class="text-slate-600 mt-1"><i class="fa-regular fa-envelope mr-1.5 text-slate-400"></i>{{ $order->customer_email }}</p>
                                <p class="text-slate-600 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-slate-400"></i>{{ $order->customer_phone }}</p>
                            </div>

                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Delivery Address</h3>
                                <p class="text-slate-800 font-medium whitespace-pre-line leading-relaxed">
                                    <i class="fa-solid fa-location-dot mr-1.5 text-rose-500"></i>{{ $order->delivery_address }}
                                </p>
                                @if(!empty($order->notes))
                                    <div class="mt-2.5 p-2 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900">
                                        <strong>Notes:</strong> {{ $order->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right: Financial Breakdown (4 Cols) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-6 sticky top-28">

                        <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">
                            Payment Breakdown
                        </h2>

                        <!-- Cost Breakdown -->
                        <div class="space-y-3 text-xs sm:text-sm text-slate-600">
                            <div class="flex items-center justify-between">
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
                            <div class="flex items-center justify-between">
                                <span>Shipping Charges</span>
                                @if((float)$order->shipping_charges == 0.0)
                                    <span class="font-bold text-emerald-600 uppercase text-xs">FREE</span>
                                @else
                                    <span class="font-bold text-slate-900">${{ number_format((float)$order->shipping_charges, 2) }}</span>
                                @endif
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-base font-extrabold text-slate-900">
                                <span>Total Amount</span>
                                <span class="text-2xl text-brand-600 font-black">${{ number_format((float)$order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Method Meta -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Payment Mode:</span>
                                <span class="font-bold text-slate-900 uppercase">{{ $order->payment_method }} (Cash on Delivery)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Payment Status:</span>
                                <span class="font-bold text-amber-700 capitalize">{{ $order->payment_status }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-2 space-y-2.5">
                            @if(auth()->check() && $order->user_id && auth()->id() === $order->user_id && $order->status === 'pending')
                                <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel Order #{{ $order->order_number }}? This will release reserved items and cannot be undone.');">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full py-2.5 px-4 bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-700 border border-rose-200 font-semibold rounded-xl text-xs transition-colors inline-flex items-center justify-center gap-2"
                                    >
                                        <i class="fa-solid fa-ban text-xs"></i>
                                        <span>Cancel Order</span>
                                    </button>
                                </form>
                            @endif

                            <a
                                href="{{ route('shop.index') }}"
                                class="w-full py-3 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs transition-colors inline-flex items-center justify-center gap-2 shadow-sm"
                            >
                                <i class="fa-solid fa-bag-shopping text-xs"></i>
                                <span>Continue Shopping Products</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
