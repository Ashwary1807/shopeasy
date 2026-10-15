@extends('layouts.admin')

@section('title', 'Notifications - ShopEasy Admin')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header & Quick Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    <span>Admin Notifications</span>
                    @if($unreadCount > 0)
                        <span class="text-xs px-2.5 py-1 rounded-full bg-rose-500 text-white font-extrabold shadow-sm">
                            {{ $unreadCount }} Unread
                        </span>
                    @endif
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Track real-time customer order placements and store activities.
                </p>
            </div>

            <div class="flex items-center gap-3">
                @if($unreadCount > 0)
                    <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer"
                        >
                            <i class="fa-solid fa-check-double text-xs"></i>
                            <span>Mark All Read</span>
                        </button>
                    </form>
                @endif

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-700 font-semibold shadow-sm hover:bg-slate-50 transition-colors"
                >
                    <i class="fa-solid fa-box-open text-xs text-slate-500"></i>
                    <span>Orders</span>
                </a>
            </div>
        </div>

        <!-- Filter Tabs & Search Bar -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-4 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <!-- Status Filter Tabs -->
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('admin.notifications.index', array_filter(['filter' => 'all', 'search' => $search])) }}"
                        class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ ($filter === 'all') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        All Notifications ({{ $totalCount }})
                    </a>
                    <a
                        href="{{ route('admin.notifications.index', array_filter(['filter' => 'unread', 'search' => $search])) }}"
                        class="px-4 py-2 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 {{ ($filter === 'unread') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        <span>Unread</span>
                        <span class="px-1.5 py-0.2 text-[10px] font-extrabold rounded-full {{ ($filter === 'unread') ? 'bg-rose-500 text-white' : 'bg-rose-100 text-rose-700' }}">
                            {{ $unreadCount }}
                        </span>
                    </a>
                </div>

                <!-- Search Input -->
                <div class="w-full lg:w-80">
                    <form action="{{ route('admin.notifications.index') }}" method="GET" class="relative">
                        @if($filter !== 'all')
                            <input type="hidden" name="filter" value="{{ $filter }}">
                        @endif
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search order #, customer..."
                            class="w-full pl-9 pr-9 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50"
                        >
                        @if(!empty($search))
                            <a
                                href="{{ route('admin.notifications.index', ['filter' => $filter]) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                title="Clear search"
                            >
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </form>
                </div>

            </div>
        </div>

        <!-- Notifications Table / Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
            @if($notifications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6">Order Details</th>
                                <th class="py-3.5 px-6">Customer</th>
                                <th class="py-3.5 px-6">Amount</th>
                                <th class="py-3.5 px-6">Received</th>
                                <th class="py-3.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($notifications as $notification)
                                <tr class="transition-colors {{ !$notification->isRead() ? 'bg-indigo-50/30 hover:bg-indigo-50/60' : 'hover:bg-slate-50/60' }}">
                                    <!-- Status Dot -->
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if(!$notification->isRead())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-brand-100 text-brand-700 text-[11px] font-extrabold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-pulse"></span>
                                                <span>New</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[11px] font-semibold">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                <span>Read</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Order Info -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs flex-shrink-0 {{ !$notification->isRead() ? 'bg-brand-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600' }}">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                            </div>
                                            <div>
                                                <a
                                                    href="{{ route('admin.notifications.markRead', ['id' => $notification->id, 'redirect_to_order' => 1]) }}"
                                                    class="font-extrabold text-slate-900 hover:text-brand-600 transition-colors"
                                                    title="View order {{ $notification->order_number }}"
                                                >
                                                    {{ $notification->order_number }}
                                                </a>
                                                <div class="text-[11px] text-slate-500 mt-0.5">
                                                    {{ $notification->title ?? 'New Order Received' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Customer Info -->
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-800">{{ $notification->customer_name }}</div>
                                        @if(!empty($notification->data['customer_email']))
                                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $notification->data['customer_email'] }}</div>
                                        @endif
                                    </td>

                                    <!-- Amount -->
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="font-black text-slate-900 text-sm">
                                            ${{ number_format((float) $notification->total_amount, 2) }}
                                        </span>
                                    </td>

                                    <!-- Timestamp -->
                                    <td class="py-4 px-6 whitespace-nowrap text-slate-500">
                                        <div class="font-semibold text-slate-700">
                                            {{ $notification->created_at->format('M d, Y h:i A') }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(!$notification->isRead())
                                                <a
                                                    href="{{ route('admin.notifications.markRead', $notification->id) }}"
                                                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition-colors"
                                                    title="Mark as read"
                                                >
                                                    <i class="fa-solid fa-check mr-1 text-[10px]"></i> Mark Read
                                                </a>
                                            @endif

                                            <a
                                                href="{{ route('admin.notifications.markRead', ['id' => $notification->id, 'redirect_to_order' => 1]) }}"
                                                class="px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition-colors"
                                                title="View full order details"
                                            >
                                                <span>View Order</span>
                                                <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="p-4 sm:p-6 border-t border-slate-100">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl shadow-sm">
                        <i class="fa-regular fa-bell-slash"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">No notifications found</h3>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto mb-6">
                        @if(!empty($search))
                            No notifications matched your search query "{{ $search }}". Try searching with different terms.
                        @elseif($filter === 'unread')
                            Great job! You have no unread notifications right now.
                        @else
                            You have no notifications yet. Whenever customers place orders, alerts will be recorded here automatically.
                        @endif
                    </p>
                    @if(!empty($search) || $filter !== 'all')
                        <a
                            href="{{ route('admin.notifications.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors"
                        >
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Reset Filters</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>

    </div>

@endsection
