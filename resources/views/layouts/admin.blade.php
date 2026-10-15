<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Portal - ShopEasy')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS (via official Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Admin Header -->
    <header class="sticky top-0 z-40 bg-slate-900 text-white shadow-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Admin Brand -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-extrabold tracking-tight text-white">
                                Shop<span class="text-brand-400">Easy</span>
                            </span>
                            <span class="text-[10px] uppercase tracking-widest bg-brand-500/20 text-brand-300 font-extrabold px-2 py-0.5 rounded border border-brand-500/30">
                                Admin Portal
                            </span>
                        </div>
                    </a>

                    <!-- Admin Navigation -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-brand-400' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-chart-line mr-1.5 text-xs"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-brand-400' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-box-open mr-1.5 text-xs"></i> Orders Management
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-brand-400' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            <i class="fa-solid fa-tags mr-1.5 text-xs"></i> Products Management
                        </a>
                    </nav>
                </div>

                <!-- Right Admin Action Controls -->
                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('home') }}"
                        target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700"
                        title="Open customer storefront in a new tab"
                    >
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        <span>View Storefront</span>
                    </a>

                    @if(session('admin_authenticated'))
                        <!-- Notification Bell & Dropdown -->
                        <div class="relative" id="adminNotificationWrapper">
                            <button
                                type="button"
                                id="adminNotificationBtn"
                                onclick="toggleNotificationDropdown()"
                                class="relative p-2 text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500 cursor-pointer"
                                aria-label="View notifications"
                                aria-expanded="false"
                                aria-haspopup="true"
                                title="Order Notifications"
                            >
                                <i class="fa-solid fa-bell text-sm"></i>
                                @if(($unreadAdminNotificationsCount ?? 0) > 0)
                                    <span id="adminNotificationBadge" class="absolute -top-1 -right-1 flex h-4 min-w-[16px] px-1 items-center justify-center rounded-full bg-rose-500 text-[10px] font-extrabold text-white ring-2 ring-slate-900 animate-pulse">
                                        {{ $unreadAdminNotificationsCount > 99 ? '99+' : $unreadAdminNotificationsCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                id="adminNotificationDropdown"
                                class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 text-slate-800 z-50 overflow-hidden transform transition-all"
                            >
                                <!-- Header -->
                                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Notifications</h3>
                                        @if(($unreadAdminNotificationsCount ?? 0) > 0)
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-700">
                                                {{ $unreadAdminNotificationsCount }} new
                                            </span>
                                        @endif
                                    </div>
                                    @if(($unreadAdminNotificationsCount ?? 0) > 0)
                                        <form action="{{ route('admin.notifications.markAllRead') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-semibold text-brand-600 hover:text-brand-800 hover:underline cursor-pointer">
                                                Mark all as read
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Notification Items List -->
                                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                                    @forelse($recentAdminNotifications ?? [] as $notification)
                                        <div class="p-3 transition-colors {{ !$notification->isRead() ? 'bg-indigo-50/40 hover:bg-indigo-50/70' : 'hover:bg-slate-50' }}">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center text-xs {{ !$notification->isRead() ? 'bg-brand-600 text-white shadow-sm' : 'bg-slate-200 text-slate-500' }}">
                                                    <i class="fa-solid fa-bag-shopping"></i>
                                                </div>
                                                <div class="flex-grow min-w-0">
                                                    <div class="flex items-center justify-between gap-1">
                                                        <a
                                                            href="{{ route('admin.notifications.markRead', ['id' => $notification->id, 'redirect_to_order' => 1]) }}"
                                                            class="text-xs font-bold text-slate-900 hover:text-brand-600 truncate block transition-colors"
                                                            title="View order {{ $notification->order_number }}"
                                                        >
                                                            {{ $notification->title ?? 'New Order Received' }}
                                                        </a>
                                                        @if(!$notification->isRead())
                                                            <span class="w-2 h-2 rounded-full bg-brand-600 flex-shrink-0" title="Unread"></span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-slate-600 line-clamp-1 mt-0.5">
                                                        {{ $notification->customer_name }} &bull; <span class="font-bold text-slate-800">${{ number_format((float)$notification->total_amount, 2) }}</span>
                                                    </p>
                                                    <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1.5 pt-1 border-t border-slate-100/60">
                                                        <span><i class="fa-regular fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                                                        <div class="flex items-center gap-2">
                                                            @if(!$notification->isRead())
                                                                <a href="{{ route('admin.notifications.markRead', $notification->id) }}" class="text-slate-500 hover:text-slate-800 font-medium hover:underline">
                                                                    Mark read
                                                                </a>
                                                                <span class="text-slate-300">&bull;</span>
                                                            @endif
                                                            <a href="{{ route('admin.notifications.markRead', ['id' => $notification->id, 'redirect_to_order' => 1]) }}" class="text-brand-600 hover:text-brand-800 font-bold hover:underline">
                                                                Order &rarr;
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-6 text-center text-slate-500">
                                            <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-sm shadow-sm">
                                                <i class="fa-regular fa-bell-slash"></i>
                                            </div>
                                            <p class="text-xs font-bold text-slate-700">No notifications yet</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Real-time alerts will appear here when customers complete orders.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Footer -->
                                <div class="p-2.5 bg-slate-50 border-t border-slate-200 text-center">
                                    <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center justify-center w-full py-1 text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors">
                                        <span>View all notifications</span>
                                        <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pl-3 border-l border-slate-700">
                            <span class="hidden lg:inline text-xs text-slate-400 font-medium">
                                <i class="fa-regular fa-user mr-1 text-slate-500"></i>{{ session('admin_email', 'Administrator') }}
                            </span>

                            <!-- Admin Logout Trigger Button (Task 2) -->
                            <button
                                type="button"
                                onclick="openAdminLogoutModal()"
                                class="px-3 py-1.5 text-xs font-semibold text-rose-300 hover:text-rose-100 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 rounded-lg transition-colors inline-flex items-center gap-1.5 cursor-pointer"
                                title="Sign out of the admin portal"
                            >
                                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow">
        <!-- Flash Notification Banners -->
        @if (session('success') || session('error') || session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                @if (session('success'))
                    <div class="flex items-center justify-between p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-center justify-between p-4 mb-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="flex items-center justify-between p-4 mb-4 text-sm text-blue-800 rounded-2xl bg-blue-50 border border-blue-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-info text-blue-600 text-base"></i>
                            <span class="font-medium">{{ session('info') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-800 p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Admin Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} ShopEasy Admin Portal. All rights reserved.</span>
            <span class="text-slate-400">Strictly Authorized Personnel Only</span>
        </div>
    </footer>

    @if(session('admin_authenticated'))
        <!-- Admin Logout Confirmation Modal (Task 2) -->
        <div id="adminLogoutModal" class="hidden fixed inset-0 z-[100] items-center justify-center overflow-y-auto p-4 sm:p-0" aria-labelledby="adminLogoutModalTitle" role="dialog" aria-modal="true">
            <!-- Dark Translucent Backdrop -->
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" onclick="closeAdminLogoutModal()"></div>

            <!-- Centered Modal Content Box -->
            <div class="relative z-10 w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-slate-200 my-8 mx-auto">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl mx-auto mb-4 shadow-sm">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-bold text-slate-900" id="adminLogoutModalTitle">
                        Are you sure you want to logout?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-2">
                        You will be signed out of the ShopEasy Admin Portal. You will need your administrator credentials to sign back in.
                    </p>
                </div>
                <div class="mt-6 flex flex-col-reverse sm:flex-row gap-3">
                    <button
                        type="button"
                        onclick="closeAdminLogoutModal()"
                        class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                        @csrf
                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all cursor-pointer"
                        >
                            Yes, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openAdminLogoutModal() {
                const modal = document.getElementById('adminLogoutModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                }
            }

            function closeAdminLogoutModal() {
                const modal = document.getElementById('adminLogoutModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAdminLogoutModal();
                    const dropdown = document.getElementById('adminNotificationDropdown');
                    if (dropdown && !dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('hidden');
                        const btn = document.getElementById('adminNotificationBtn');
                        if (btn) btn.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            function toggleNotificationDropdown() {
                const dropdown = document.getElementById('adminNotificationDropdown');
                const btn = document.getElementById('adminNotificationBtn');
                if (!dropdown) return;
                const isHidden = dropdown.classList.contains('hidden');
                if (isHidden) {
                    dropdown.classList.remove('hidden');
                    if (btn) btn.setAttribute('aria-expanded', 'true');
                } else {
                    dropdown.classList.add('hidden');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            }

            document.addEventListener('click', function(e) {
                const wrapper = document.getElementById('adminNotificationWrapper');
                const dropdown = document.getElementById('adminNotificationDropdown');
                const btn = document.getElementById('adminNotificationBtn');
                if (wrapper && dropdown && !wrapper.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
