<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">

            <!-- Brand Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-brand-500/25 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold tracking-tight text-slate-900 group-hover:text-brand-600 transition-colors">
                            Shop<span class="text-brand-600">Easy</span>
                        </span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400 font-bold -mt-1">Online Store</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-semibold {{ request()->routeIs('home') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50' }} rounded-lg transition-colors">
                        Home
                    </a>
                    <a href="{{ route('shop.index') }}" class="px-3 py-2 text-sm font-semibold {{ request()->routeIs('shop.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50' }} rounded-lg transition-colors">
                        Shop
                    </a>
                    <a href="{{ route('home') }}#categories" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-50 rounded-lg transition-colors">
                        Categories
                    </a>
                    <a href="{{ route('home') }}#promotions" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-50 rounded-lg transition-colors">
                        Deals
                    </a>
                </nav>
            </div>

            <!-- Search Bar (Functional Form) -->
            <div class="hidden lg:flex flex-1 max-w-md mx-4">
                <form action="{{ route('shop.index') }}" method="GET" class="relative w-full">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search products, brands, categories..."
                        class="w-full pl-10 pr-10 py-2 text-sm bg-slate-100/80 border border-slate-200 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all text-slate-800 placeholder-slate-400"
                    >
                    @if(request('search'))
                        <a href="{{ route('shop.index', array_filter(['category' => request('category')])) }}" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600" title="Clear search">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Right Action Items (Wishlist, Cart, Account, Mobile Menu Toggle) -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Install App Button (PWA) -->
                <button
                    type="button"
                    onclick="triggerPwaInstall()"
                    class="pwa-install-trigger inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold border border-brand-200/80 transition-all shadow-sm hover:shadow cursor-pointer"
                    title="Install ShopEasy Web App on your device"
                >
                    <i class="fa-solid fa-mobile-screen-button text-xs text-brand-600"></i>
                    <span class="hidden md:inline">Install App</span>
                </button>

                @php
                    $navWishlistCount = count(session('wishlist', []));
                @endphp

                <!-- Wishlist Button -->
                <a
                    href="{{ route('wishlist.index') }}"
                    class="relative p-2.5 text-slate-600 hover:text-brand-600 hover:bg-slate-100 rounded-full transition-colors {{ request()->routeIs('wishlist.*') ? 'text-brand-600 bg-brand-50' : '' }}"
                    title="Wishlist"
                >
                    <i class="{{ $navWishlistCount > 0 ? 'fa-solid text-rose-500' : 'fa-regular' }} fa-heart text-lg"></i>
                    <span class="absolute top-1 right-1 min-w-[1rem] h-4 px-1 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow">
                        {{ $navWishlistCount }}
                    </span>
                </a>

                @php
                    $navCart = session('cart', []);
                    $cartTotalQty = 0;
                    if (is_array($navCart)) {
                        foreach ($navCart as $item) {
                            $cartTotalQty += (int)($item['quantity'] ?? 0);
                        }
                    }
                @endphp

                <!-- Cart Button with Total Quantity Counter (Requirement 1) -->
                <a
                    href="{{ route('cart.index') }}"
                    class="relative flex items-center gap-2 px-3.5 py-2 text-slate-700 bg-slate-100 hover:bg-brand-50 hover:text-brand-600 rounded-full transition-colors border border-slate-200 {{ request()->routeIs('cart.*') ? 'bg-brand-50 text-brand-600 border-brand-200' : '' }}"
                    title="Shopping Cart"
                >
                    <div class="relative">
                        <i class="fa-solid fa-cart-shopping text-base"></i>
                        <span class="absolute -top-2 -right-2.5 min-w-[1rem] h-4 px-1 bg-brand-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow">
                            {{ $cartTotalQty }}
                        </span>
                    </div>
                    <span class="hidden sm:inline text-sm font-semibold ml-1">Cart</span>
                </a>

                <!-- User Account Dropdown / Auth State (Feature 4 & 5) -->
                @guest
                    <div class="hidden sm:flex items-center gap-1.5 pl-1.5 border-l border-slate-200">
                        <a
                            href="{{ route('login') }}"
                            class="px-3 py-2 text-xs font-bold text-slate-700 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition-colors {{ request()->routeIs('login') ? 'text-brand-600 bg-brand-50' : '' }}"
                        >
                            Log In
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="px-3.5 py-2 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-sm transition-all"
                        >
                            Register
                        </a>
                    </div>
                @else
                    <div class="relative hidden sm:block" id="userMenuDropdownContainer">
                        <button
                            type="button"
                            onclick="toggleUserMenu()"
                            class="flex items-center gap-2 px-3 py-1.5 text-slate-700 bg-slate-100 hover:bg-brand-50 hover:text-brand-600 rounded-full transition-colors border border-slate-200 focus:outline-none"
                            aria-expanded="false"
                            id="userMenuButton"
                        >
                            <div class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold max-w-[110px] truncate">
                                Hi, {{ Str::words(auth()->user()->name, 1, '') }}
                            </span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>

                        <!-- Dropdown Content -->
                        <div
                            id="userMenuDropdown"
                            class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150"
                        >
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-xs font-extrabold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('account.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors {{ request()->routeIs('account.index') ? 'text-brand-600 bg-brand-50' : '' }}">
                                <i class="fa-solid fa-gauge-high text-brand-600 w-4"></i>
                                <span>My Account</span>
                            </a>
                            <a href="{{ route('account.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors {{ request()->routeIs('account.profile*') ? 'text-brand-600 bg-brand-50' : '' }}">
                                <i class="fa-regular fa-id-badge text-indigo-500 w-4"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="{{ route('account.addresses.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors {{ request()->routeIs('account.addresses.*') ? 'text-brand-600 bg-brand-50' : '' }}">
                                <i class="fa-solid fa-map-location-dot text-amber-500 w-4"></i>
                                <span>Saved Addresses</span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors {{ request()->routeIs('orders.*') ? 'text-brand-600 bg-brand-50' : '' }}">
                                <i class="fa-solid fa-box-open text-slate-400 w-4"></i>
                                <span>My Orders</span>
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors {{ request()->routeIs('wishlist.*') ? 'text-brand-600 bg-brand-50' : '' }}">
                                <i class="fa-solid fa-heart text-rose-500 w-4"></i>
                                <span>Wishlist ({{ $navWishlistCount }})</span>
                            </a>
                            <div class="my-1 border-t border-slate-100"></div>
                            <button
                                type="button"
                                onclick="openLogoutModal()"
                                class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors text-left"
                            >
                                <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                <span>Log Out</span>
                            </button>
                        </div>
                    </div>
                @endguest

                <!-- Mobile Hamburger Menu Button -->
                <button
                    type="button"
                    onclick="toggleMobileMenu()"
                    class="md:hidden p-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg focus:outline-none"
                    aria-label="Toggle navigation menu"
                >
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>

        </div>

        <!-- Mobile Search Bar (Functional) -->
        <div class="lg:hidden pb-4">
            <form action="{{ route('shop.index') }}" method="GET" class="relative w-full">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="w-full pl-10 pr-10 py-2 text-sm bg-slate-100 border border-slate-200 rounded-full focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800"
                >
                @if(request('search'))
                    <a href="{{ route('shop.index', array_filter(['category' => request('category')])) }}" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600" title="Clear search">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Mobile Drawer Menu (Hidden by default) -->
    <div id="mobileMenu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2 shadow-lg">
        @auth
            <div class="px-3 py-2.5 bg-slate-50 rounded-xl mb-3 border border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        @endauth

        <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-semibold text-brand-600 bg-brand-50 rounded-lg">
            <i class="fa-solid fa-house mr-2"></i> Home
        </a>
        <a href="{{ route('shop.index') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('shop.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-100' }} rounded-lg">
            <i class="fa-solid fa-bag-shopping mr-2"></i> Shop
        </a>
        <a href="{{ route('home') }}#promotions" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-100 rounded-lg">
            <i class="fa-solid fa-tag mr-2 text-brand-600"></i> Deals &amp; Offers
        </a>
        <a href="{{ route('cart.index') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('cart.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-100' }} rounded-lg">
            <i class="fa-solid fa-cart-shopping mr-2"></i> Cart ({{ $cartTotalQty }})
        </a>
        <a href="{{ route('wishlist.index') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('wishlist.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-100' }} rounded-lg">
            <i class="fa-solid fa-heart mr-2 text-rose-500"></i> Wishlist ({{ $navWishlistCount }})
        </a>
        <a href="{{ route('orders.index') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('orders.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-100' }} rounded-lg">
            <i class="fa-solid fa-box-open mr-2"></i> {{ auth()->check() ? 'My Orders' : 'Track Orders' }}
        </a>

        <!-- Install App Option (PWA Mobile) -->
        <button
            type="button"
            onclick="toggleMobileMenu(); triggerPwaInstall();"
            class="pwa-install-trigger w-full flex items-center justify-between px-3 py-2 text-base font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-lg transition-colors text-left cursor-pointer"
        >
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-mobile-screen-button text-brand-600"></i>
                <span>Install ShopEasy App</span>
            </span>
            <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded-full bg-brand-200/70 text-brand-800">
                1-Tap Install
            </span>
        </button>
        @auth
            <a href="{{ route('account.index') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('account.index') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-100' }} rounded-lg">
                <i class="fa-solid fa-gauge-high mr-2 text-brand-600"></i> My Account
            </a>
            <a href="{{ route('account.profile') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('account.profile*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-100' }} rounded-lg">
                <i class="fa-regular fa-id-badge mr-2 text-indigo-500"></i> My Profile
            </a>
            <a href="{{ route('account.addresses.index') }}" onclick="toggleMobileMenu()" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('account.addresses.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-100' }} rounded-lg">
                <i class="fa-solid fa-map-location-dot mr-2 text-amber-500"></i> Saved Addresses
            </a>
        @endauth

        @guest
            <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-2">
                <a href="{{ route('login') }}" class="py-2.5 text-center text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="py-2.5 text-center text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors">
                    Register
                </a>
            </div>
        @else
            <div class="pt-2 border-t border-slate-100">
                <button
                    type="button"
                    onclick="openLogoutModal()"
                    class="w-full flex items-center gap-2 px-3 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 rounded-lg text-left"
                >
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Log Out</span>
                </button>
            </div>
        @endguest

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <span class="text-sm font-medium text-slate-500">Need help?</span>
            <span class="text-sm font-semibold text-brand-600">+1 (800) 123-4567</span>
        </div>
    </div>
</header>

<!-- Customer Logout Confirmation Modal (Task 1) -->
<div id="logoutModal" class="hidden fixed inset-0 z-[100] items-center justify-center overflow-y-auto p-4 sm:p-0" aria-labelledby="logoutModalTitle" role="dialog" aria-modal="true">
    <!-- Dark Translucent Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeLogoutModal()"></div>

    <!-- Centered Modal Content Box -->
    <div class="relative z-10 w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-slate-100 my-8 mx-auto">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl mx-auto mb-4 shadow-sm">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-bold text-slate-900" id="logoutModalTitle">
                Are you sure you want to logout?
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                You will need to sign back in to access checkout and view your account orders.
            </p>
        </div>
        <div class="mt-6 flex flex-col-reverse sm:flex-row gap-3">
            <button
                type="button"
                onclick="closeLogoutModal()"
                class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer"
            >
                Cancel
            </button>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
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
    function toggleUserMenu() {
        const dropdown = document.getElementById('userMenuDropdown');
        if (dropdown) dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const container = document.getElementById('userMenuDropdownContainer');
        const dropdown = document.getElementById('userMenuDropdown');
        if (container && dropdown && !container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    function openLogoutModal() {
        const dropdown = document.getElementById('userMenuDropdown');
        if (dropdown) dropdown.classList.add('hidden');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) mobileMenu.classList.add('hidden');
        const modal = document.getElementById('logoutModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>
