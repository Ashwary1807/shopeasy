@extends('layouts.app')

@section('title', 'ShopEasy - Modern E-Commerce Storefront')

@section('content')

    <!-- ===================================================================== -->
    <!-- 1. HERO BANNER SECTION                                                -->
    <!-- ===================================================================== -->
    <section class="relative bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white overflow-hidden">
        <!-- Subtle background glow effects -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Hero Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs sm:text-sm font-medium tracking-wide">
                        {{ $hero['badge'] }}
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        {{ $hero['title'] }}
                    </h1>

                    <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        {{ $hero['subtitle'] }}
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a
                            href="#featured-products"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl shadow-lg shadow-brand-600/30 hover:shadow-brand-600/50 hover:-translate-y-0.5 transition-all text-sm tracking-wide"
                        >
                            <span>{{ $hero['cta_primary'] }}</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                        <a
                            href="#categories"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/15 text-white font-medium rounded-xl border border-white/20 hover:border-white/30 backdrop-blur-sm transition-all text-sm"
                        >
                            <span>{{ $hero['cta_secondary'] }}</span>
                        </a>
                    </div>

                    <!-- Micro Social Proof -->
                    <div class="pt-6 border-t border-slate-800/80 flex items-center justify-center lg:justify-start gap-6 text-xs text-slate-400">
                        <div class="flex items-center gap-1.5 text-amber-400">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                            <span class="text-slate-300 font-bold ml-1">4.9 / 5</span>
                        </div>
                        <span class="text-slate-500">|</span>
                        <span>Over <strong>15,000+</strong> Happy Customers</span>
                    </div>
                </div>

                <!-- Right Hero Image Showcase -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Decorative Frame -->
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/10 aspect-[4/3] sm:aspect-[4/3] group">
                            <img
                                src="{{ $hero['image'] }}"
                                alt="ShopEasy Collection"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                            <!-- Floating Tag inside Hero Image -->
                            <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase tracking-wider text-brand-300 font-bold block">New Collection</span>
                                    <span class="text-sm font-bold">Trending Essentials 2026</span>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/80 text-white font-semibold">Available Now</span>
                            </div>
                        </div>

                        <!-- Floating Badge -->
                        <div class="hidden sm:flex absolute -top-5 -left-5 bg-white text-slate-900 py-2.5 px-4 rounded-2xl shadow-xl items-center gap-3 border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold leading-tight">Flash Sale Live</p>
                                <p class="text-[11px] text-slate-500">Up to 50% Off</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- 2. TRUST / VALUE PERKS STRIP                                          -->
    <!-- ===================================================================== -->
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($perks as $perk)
                    <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid {{ $perk['icon'] }}"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $perk['title'] }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $perk['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- 3. PRODUCT CATEGORIES SECTION                                         -->
    <!-- ===================================================================== -->
    <section id="categories" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest font-extrabold text-brand-600 block mb-1">Browse By Category</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Explore Popular Categories</h2>
                </div>
                <a
                    href="{{ route('shop.index') }}"
                    class="text-xs sm:text-sm font-semibold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1.5 transition-colors self-start sm:self-auto"
                >
                    <span>View All Categories</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
                @foreach ($categories as $cat)
                    <a
                        href="{{ route('shop.index', ['category' => $cat['name']]) }}"
                        class="group cursor-pointer bg-white rounded-2xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col"
                    >
                        <!-- Category Image Container -->
                        <div class="relative aspect-square overflow-hidden bg-slate-100">
                            <img
                                src="{{ $cat['image'] }}"
                                alt="{{ $cat['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>

                            <!-- Category Badge -->
                            <span class="absolute top-2.5 right-2.5 text-[10px] font-bold px-2 py-0.5 bg-white/90 backdrop-blur-sm rounded-full text-slate-800 shadow-sm">
                                {{ $cat['badge'] }}
                            </span>
                        </div>

                        <!-- Category Meta -->
                        <div class="p-3.5 text-center flex-grow flex flex-col justify-center">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors truncate">
                                {{ $cat['name'] }}
                            </h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ $cat['item_count'] }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- 4. FEATURED PRODUCTS (8 SAMPLE PRODUCT CARDS)                         -->
    <!-- ===================================================================== -->
    <section id="featured-products" class="py-16 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest font-extrabold text-brand-600 block mb-1">Curated Selection</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Featured Products</h2>
                    <p class="text-sm text-slate-500 mt-1">Handpicked top deals and trending products with exclusive discounts.</p>
                </div>

                <!-- Quick Category Pill Filter -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-2 sm:pb-0">
                    <a href="{{ route('shop.index') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-full bg-slate-900 text-white shadow-sm hover:bg-slate-800 transition-colors">All</a>
                    <a href="{{ route('shop.index', ['category' => 'Electronics']) }}" class="px-3.5 py-1.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600 transition-colors">Electronics</a>
                    <a href="{{ route('shop.index', ['category' => 'Fashion & Apparel']) }}" class="px-3.5 py-1.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600 transition-colors">Fashion</a>
                    <a href="{{ route('shop.index', ['category' => 'Home & Living']) }}" class="px-3.5 py-1.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600 transition-colors">Home</a>
                </div>
            </div>

            <!-- Product Grid: Dynamic Product Cards via Eloquent -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="group bg-white rounded-2xl border border-slate-200/90 hover:border-slate-300 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden relative">

                        <!-- Product Image Area (Clickable to Details Page) -->
                        <div class="relative aspect-square overflow-hidden bg-slate-100 block">
                            <a href="{{ route('product.show', $product->id) }}" class="block w-full h-full">
                                <img
                                    src="{{ $product->image ?? $product['image'] }}"
                                    alt="{{ $product->name ?? $product['name'] }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-108 transition-transform duration-500"
                                    loading="lazy"
                                >
                            </a>

                            <!-- Top Left Badges: Status & Discount -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start pointer-events-none">
                                @if (!empty($product->badge ?? $product['badge'] ?? null))
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold text-white shadow-sm {{ $product->badge_color ?? $product['badge_color'] ?? 'bg-brand-600' }}">
                                        {{ $product->badge ?? $product['badge'] }}
                                    </span>
                                @endif
                                @if (!empty($product->discount_percent ?? $product['discount_percent'] ?? null))
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">
                                        -{{ $product->discount_percent ?? $product['discount_percent'] }}%
                                    </span>
                                @endif
                            </div>

                            @php
                                $inWishlist = in_array($product->id ?? $product['id'], session('wishlist', []), true);
                            @endphp

                            <!-- Top Right Wishlist Action Toggle -->
                            <form action="{{ route('wishlist.toggle', $product->id ?? $product['id']) }}" method="POST" class="absolute top-3 right-3 z-10">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-9 h-9 rounded-full bg-white/90 hover:bg-white text-slate-500 hover:text-rose-500 shadow-sm flex items-center justify-center transition-all duration-200 opacity-90 group-hover:opacity-100 {{ $inWishlist ? '!text-rose-500 bg-white' : '' }}"
                                    title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                                >
                                    <i class="{{ $inWishlist ? 'fa-solid' : 'fa-regular' }} fa-heart text-sm"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Product Content Area -->
                        <div class="p-4 flex-grow flex flex-col justify-between">

                            <div>
                                <!-- Category & Rating -->
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="text-brand-600 hover:underline font-semibold tracking-wide uppercase text-[10px]">
                                        {{ $product->category ?? $product['category'] }}
                                    </a>
                                    @php
                                        $realReviewsCount = $product->approved_reviews_count ?? ($product instanceof \App\Models\Product && $product->relationLoaded('reviews') ? $product->reviews->where('status', 'approved')->count() : null);
                                    @endphp
                                    @if($realReviewsCount && $realReviewsCount > 0)
                                        <div class="flex items-center gap-1 text-amber-500 font-semibold text-[11px]">
                                            <i class="fa-solid fa-star text-[10px]"></i>
                                            <span>{{ number_format((float)$product->rating, 1) }}</span>
                                            <span class="text-slate-400 font-normal">({{ $realReviewsCount }})</span>
                                        </div>
                                    @else
                                        <div class="text-[11px] text-slate-400 font-normal flex items-center gap-1">
                                            <i class="fa-regular fa-star text-[10px]"></i>
                                            <span>No reviews yet</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Title (Clickable to Details Page) -->
                                <a href="{{ route('product.show', $product->id) }}">
                                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-2 leading-snug" title="{{ $product->name ?? $product['name'] }}">
                                        {{ $product->name ?? $product['name'] }}
                                    </h3>
                                </a>
                            </div>

                            <!-- Pricing & Cart Button -->
                            <div class="pt-4 mt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                                <div>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-lg font-extrabold text-slate-900">${{ number_format((float)($product->price ?? $product['price']), 2) }}</span>
                                        @if(!empty($product->original_price ?? $product['original_price'] ?? null))
                                            <span class="text-xs text-slate-400 line-through">${{ number_format((float)($product->original_price ?? $product['original_price']), 2) }}</span>
                                        @endif
                                    </div>
                                    @if(($product->stock ?? $product['stock'] ?? 0) > 0)
                                        <span class="text-[10px] text-emerald-600 font-semibold block">In Stock ({{ $product->stock ?? $product['stock'] }})</span>
                                    @else
                                        <span class="text-[10px] text-rose-500 font-semibold block">Out of Stock</span>
                                    @endif
                                </div>

                                <!-- Add to Cart Button (Functional Phase C) -->
                                @php
                                    $itemStock = $product->stock ?? $product['stock'] ?? 0;
                                    $itemId = $product->id ?? $product['id'];
                                @endphp
                                @if($itemStock > 0)
                                    <form action="{{ route('cart.add', $itemId) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-semibold rounded-xl shadow-sm hover:shadow transition-all group/btn"
                                            title="Add to Cart"
                                        >
                                            <i class="fa-solid fa-cart-plus text-xs group-hover/btn:scale-110 transition-transform"></i>
                                            <span>Add</span>
                                        </button>
                                    </form>
                                @else
                                    <button
                                        type="button"
                                        disabled
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-200 text-slate-400 text-xs font-semibold rounded-xl cursor-not-allowed"
                                        title="Out of stock"
                                    >
                                        <span>Sold Out</span>
                                    </button>
                                @endif
                            </div>

                        </div>

                    </div>
                @empty
                    <!-- Clean Empty State when no products are in the database -->
                    <div class="col-span-full py-16 px-6 text-center bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl shadow-sm">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No Products Available Yet</h3>
                        <p class="text-sm text-slate-500 max-w-md mx-auto mt-1 mb-5">
                            We're currently stocking our shelves. Once the database migration and seeder are executed, dynamic products will appear here automatically!
                        </p>
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-600 rounded-xl text-xs font-mono border border-slate-200 shadow-sm">
                            <i class="fa-solid fa-database text-brand-600"></i>
                            <span>Waiting for migration &amp; seeder execution</span>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- View More Button -->
            <div class="text-center mt-12">
                <a
                    href="{{ route('shop.index') }}"
                    class="inline-flex items-center gap-2 px-8 py-3.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition-all"
                >
                    <span>Explore Full Catalog ({{ $products->count() }}+ Products)</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- 5. PROMOTIONAL CALLOUT BANNER                                         -->
    <!-- ===================================================================== -->
    <section id="promotions" class="py-12 bg-slate-900 text-white relative overflow-hidden scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-gradient-to-r from-brand-900 via-indigo-900 to-slate-900 rounded-3xl p-8 sm:p-12 border border-indigo-700/30 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-8">

                <div class="space-y-3 max-w-2xl text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2">
                        <span class="inline-block px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 text-xs font-extrabold tracking-wider border border-rose-500/30 uppercase">
                            {{ $promo['tag'] }}
                        </span>
                        <span class="inline-block px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 text-xs font-bold border border-brand-500/30">
                            50% OFF
                        </span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        {{ $promo['title'] }}
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        {{ $promo['description'] }}
                    </p>
                    <div class="pt-1 flex flex-wrap items-center justify-center lg:justify-start gap-3 text-xs text-brand-200">
                        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-400 text-[11px]"></i> 50% Off Eligible Categories</span>
                        <span class="text-slate-500">•</span>
                        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-cart-shopping text-brand-400 text-[11px]"></i> Min. $30.00 Subtotal</span>
                        <span class="text-slate-500">•</span>
                        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-amber-400 text-[11px]"></i> Max. $25.00 Discount</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                    <div class="px-5 py-3 rounded-xl bg-white/10 border border-dashed border-white/30 text-center font-mono font-bold tracking-widest text-brand-300 text-sm select-all">
                        {{ $promo['coupon_code'] }}
                    </div>
                    <button
                        type="button"
                        onclick="copyPromoCode('{{ $promo['coupon_code'] }}')"
                        class="px-6 py-3.5 bg-white hover:bg-slate-100 active:bg-slate-200 text-slate-900 font-bold rounded-xl text-xs tracking-wide shadow-lg transition-all inline-flex items-center gap-2 cursor-pointer hover:scale-[1.02]"
                    >
                        <i class="fa-regular fa-copy text-sm text-slate-700"></i>
                        <span>{{ $promo['button_text'] }}</span>
                    </button>
                </div>

            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- 5.5. PWA DOWNLOAD / INSTALL PROMOTION SECTION                         -->
    <!-- ===================================================================== -->
    <section class="py-14 bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 text-white relative overflow-hidden border-t border-indigo-950">
        <!-- Glow accents -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-4 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/20 border border-brand-500/30 text-brand-300 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-mobile-screen-button text-xs"></i>
                        <span>Progressive Web App</span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                        Experience ShopEasy on Your Device
                    </h2>

                    <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Install the official ShopEasy web app for lightning-fast catalog browsing, instant checkout, and a seamless native-like shopping experience on iOS, Android, and Desktop.
                    </p>

                    <!-- Feature checkmarks -->
                    <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-4 text-xs text-brand-200">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-bolt text-amber-400"></i>
                            <span>Instant 1-Tap Access</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                            <span>Secure &amp; Encrypted</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-box-open text-sky-400"></i>
                            <span>Real-Time Order Tracking</span>
                        </div>
                    </div>
                </div>

                <!-- Right Action / Device Card -->
                <div class="lg:col-span-5 flex flex-col sm:flex-row lg:flex-col items-center justify-center gap-4">
                    <div class="w-full max-w-sm p-6 rounded-3xl bg-white/10 backdrop-blur-md border border-white/20 text-center shadow-2xl">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-lg mx-auto mb-4">
                            <i class="fa-solid fa-bag-shopping text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">ShopEasy Official App</h3>
                        <p class="text-xs text-slate-300 mb-5">Official PWA (No APK or store download required) &bull; Zero storage footprint</p>

                        <button
                            type="button"
                            onclick="triggerPwaInstall()"
                            class="w-full py-3.5 px-6 rounded-2xl bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-sm shadow-xl hover:shadow-2xl hover:scale-[1.02] transition-all flex items-center justify-center gap-2.5 cursor-pointer"
                        >
                            <i class="fa-solid fa-mobile-screen-button text-brand-600"></i>
                            <span>Install ShopEasy App</span>
                        </button>

                        <p class="text-[10px] text-slate-400 mt-3">
                            Compatible with iOS Safari, Chrome, Edge, and Android browsers.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- 6. NEWSLETTER CALLOUT                                                 -->
    <!-- ===================================================================== -->
    <section class="py-16 bg-slate-100/60 border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-600 mx-auto flex items-center justify-center text-xl shadow-sm">
                <i class="fa-regular fa-envelope"></i>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Don't Miss Out on Exclusive Offers
            </h2>
            <p class="text-sm text-slate-600 max-w-lg mx-auto">
                Join our newsletter and receive personalized deals, seasonal discounts, and early access to new product drops directly in your inbox.
            </p>
            <form onsubmit="event.preventDefault(); showDemoNotice('Newsletter subscription is a visual preview in Step 1');" class="flex flex-col sm:flex-row gap-2.5 max-w-md mx-auto pt-2">
                <input
                    type="email"
                    placeholder="Enter your email address"
                    required
                    class="flex-grow px-4 py-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white"
                >
                <button
                    type="submit"
                    class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors shrink-0"
                >
                    Subscribe Now
                </button>
            </form>
            <p class="text-[11px] text-slate-400">We respect your privacy. No spam ever.</p>
        </div>
    </section>

@endsection
