@extends('layouts.admin')

@section('title', 'Orders Management - ShopEasy Admin')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header & Quick Store Link -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Orders Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Manage fulfillment workflows, track customer orders, and update cash-on-delivery statuses.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-600 font-semibold shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Live Database Sync</span>
                </span>
            </div>
        </div>

        <!-- 1. SUMMARY METRIC COUNTER CARDS (Requirement 2) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-8">

            <!-- All Orders -->
            <a href="{{ route('admin.orders.index') }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-brand-300 hover:shadow transition-all group">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1 group-hover:text-brand-600">Total Orders</span>
                <span class="text-2xl font-black text-slate-900 block">{{ $metrics['total'] }}</span>
            </a>

            <!-- Pending Orders -->
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-amber-300 hover:shadow transition-all group {{ $selectedStatus === 'pending' ? 'ring-2 ring-amber-500/20 border-amber-300 bg-amber-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 block mb-1">Pending</span>
                <span class="text-2xl font-black text-amber-700 block">{{ $metrics['pending'] }}</span>
            </a>

            <!-- Processing Orders -->
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-blue-300 hover:shadow transition-all group {{ $selectedStatus === 'processing' ? 'ring-2 ring-blue-500/20 border-blue-300 bg-blue-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 block mb-1">Processing</span>
                <span class="text-2xl font-black text-blue-700 block">{{ $metrics['processing'] }}</span>
            </a>

            <!-- Shipped Orders -->
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-indigo-300 hover:shadow transition-all group {{ $selectedStatus === 'shipped' ? 'ring-2 ring-indigo-500/20 border-indigo-300 bg-indigo-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 block mb-1">Shipped</span>
                <span class="text-2xl font-black text-indigo-700 block">{{ $metrics['shipped'] }}</span>
            </a>

            <!-- Delivered Orders -->
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-emerald-300 hover:shadow transition-all group {{ $selectedStatus === 'delivered' ? 'ring-2 ring-emerald-500/20 border-emerald-300 bg-emerald-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 block mb-1">Delivered</span>
                <span class="text-2xl font-black text-emerald-700 block">{{ $metrics['delivered'] }}</span>
            </a>

            <!-- Cancelled Orders -->
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-rose-300 hover:shadow transition-all group {{ $selectedStatus === 'cancelled' ? 'ring-2 ring-rose-500/20 border-rose-300 bg-rose-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-500 block mb-1">Cancelled</span>
                <span class="text-2xl font-black text-rose-600 block">{{ $metrics['cancelled'] }}</span>
            </a>

        </div>

        <!-- 2. SEARCH & FILTER TOOLBAR (Requirement 2) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-4 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <!-- Status Filter Tabs -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-2 lg:pb-0">
                    @php
                        $filterTabs = [
                            'all' => 'All Orders (' . $metrics['total'] . ')',
                            'pending' => 'Pending (' . $metrics['pending'] . ')',
                            'processing' => 'Processing (' . $metrics['processing'] . ')',
                            'shipped' => 'Shipped (' . $metrics['shipped'] . ')',
                            'delivered' => 'Delivered (' . $metrics['delivered'] . ')',
                            'cancelled' => 'Cancelled (' . $metrics['cancelled'] . ')',
                        ];
                    @endphp

                    @foreach ($filterTabs as $key => $label)
                        <a
                            href="{{ route('admin.orders.index', array_filter(['status' => ($key === 'all' ? null : $key), 'search' => $search])) }}"
                            class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap {{ ($selectedStatus === $key) ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <!-- Keyword Search Form -->
                <div class="w-full lg:w-80">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="relative">
                        @if($selectedStatus !== 'all')
                            <input type="hidden" name="status" value="{{ $selectedStatus }}">
                        @endif
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search order #, customer, email..."
                            class="w-full pl-9 pr-9 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all placeholder-slate-400"
                        >
                        @if(!empty($search))
                            <a
                                href="{{ route('admin.orders.index', array_filter(['status' => ($selectedStatus === 'all' ? null : $selectedStatus)])) }}"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600"
                                title="Clear search"
                            >
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </form>
                </div>

            </div>
        </div>

        <!-- 3. ORDERS DATA TABLE -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">

            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                                <th class="py-3.5 px-4 sm:px-6">Order Details</th>
                                <th class="py-3.5 px-4 sm:px-6">Customer</th>
                                <th class="py-3.5 px-4 sm:px-6 text-center">Items</th>
                                <th class="py-3.5 px-4 sm:px-6">Total Amount</th>
                                <th class="py-3.5 px-4 sm:px-6">Payment Status</th>
                                <th class="py-3.5 px-4 sm:px-6">Order Status</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-slate-50/60 transition-colors group">

                                    <!-- Order Number & Date -->
                                    <td class="py-4 px-4 sm:px-6">
                                        <a href="{{ route('admin.orders.show', $order->order_number) }}" class="font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors font-mono block">
                                            {{ $order->order_number }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">
                                            {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                                        </span>
                                    </td>

                                    <!-- Customer Details -->
                                    <td class="py-4 px-4 sm:px-6">
                                        <span class="font-bold text-slate-900 block">{{ $order->customer_name }}</span>
                                        <span class="text-[11px] text-slate-500 block truncate max-w-xs">{{ $order->customer_email }}</span>
                                        <span class="text-[11px] text-slate-400 block">{{ $order->customer_phone }}</span>
                                    </td>

                                    <!-- Items Count -->
                                    <td class="py-4 px-4 sm:px-6 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs">
                                            {{ $order->items->count() }}
                                        </span>
                                    </td>

                                    <!-- Total Amount -->
                                    <td class="py-4 px-4 sm:px-6">
                                        <span class="font-black text-slate-900 block text-sm">
                                            ${{ number_format((float)$order->total_amount, 2) }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block uppercase font-bold">
                                            {{ $order->payment_method }}
                                        </span>
                                    </td>

                                    <!-- Payment Status -->
                                    <td class="py-4 px-4 sm:px-6">
                                        @php
                                            $paymentBadge = match(strtolower($order->payment_status)) {
                                                'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'failed' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                default => 'bg-amber-100 text-amber-800 border-amber-200', // pending
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $paymentBadge }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>

                                    <!-- Order Status Badge -->
                                    <td class="py-4 px-4 sm:px-6">
                                        @php
                                            $orderBadge = match(strtolower($order->status)) {
                                                'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                default => 'bg-amber-100 text-amber-800 border-amber-200', // pending
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $orderBadge }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-4 sm:px-6 text-right">
                                        <a
                                            href="{{ route('admin.orders.show', $order->order_number) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-brand-600 active:bg-brand-700 text-white font-semibold rounded-lg text-xs transition-colors shadow-sm"
                                        >
                                            <span>Manage</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links (Requirement 2) -->
                @if($orders->hasPages())
                    <div class="p-4 sm:p-6 border-t border-slate-100 bg-slate-50/40">
                        {{ $orders->links() }}
                    </div>
                @endif

            @else
                <!-- Clean Empty State -->
                <div class="py-16 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-2xl mb-4">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No Orders Found</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-6 leading-relaxed">
                        @if(!empty($search))
                            No orders matched your search query "{{ $search }}".
                        @elseif($selectedStatus !== 'all')
                            No orders are currently in "{{ ucfirst($selectedStatus) }}" status.
                        @else
                            No customer orders have been placed in the database yet.
                        @endif
                    </p>
                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-xs transition-colors"
                    >
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset All Filters</span>
                    </a>
                </div>
            @endif

        </div>

    </div>

@endsection
