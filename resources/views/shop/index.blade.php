@extends('layouts.app')

@section('title', !empty($selectedCategory) ? $selectedCategory . ' - ShopEasy Catalog' : 'Shop All Products - ShopEasy')

@section('content')

    <!-- Catalog Page Container -->
    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('shop.index') }}" class="{{ empty($selectedCategory) && empty($search) ? 'text-brand-600 font-bold' : 'hover:text-brand-600 transition-colors' }}">
                    Shop
                </a>
                @if(!empty($selectedCategory))
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-brand-600 font-bold">{{ $selectedCategory }}</span>
                @endif
                @if(!empty($search))
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-amber-600 font-bold">Search: "{{ $search }}"</span>
                @endif
            </nav>

            <!-- Catalog Header -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 mb-8 relative overflow-hidden">
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-brand-500/5 rounded-full blur-2xl pointer-events-none"></div>
                <div class="max-w-3xl space-y-2">
                    <span class="text-xs uppercase tracking-widest font-extrabold text-brand-600">
                        {{ !empty($selectedCategory) ? 'Category Catalog' : (!empty($search) ? 'Search Results' : 'Explore Storefront') }}
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        @if(!empty($search) && !empty($selectedCategory))
                            "{{ $search }}" in {{ $selectedCategory }}
                        @elseif(!empty($search))
                            Results for "{{ $search }}"
                        @elseif(!empty($selectedCategory))
                            {{ $selectedCategory }}
                        @else
                            All Products Catalog
                        @endif
                    </h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Discover top quality essentials, trending items, and unbeatable offers. Browse by category, search by keywords, or navigate through pages to find what you love.
                    </p>
                </div>
            </div>

            <!-- Category Filter Pills Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between gap-4 mb-3">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Filter By Category:</h3>
                    @if(!empty($selectedCategory) || !empty($search))
                        <a
                            href="{{ route('shop.index') }}"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 inline-flex items-center gap-1 transition-colors"
                        >
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Clear All Filters</span>
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                    <!-- All Products Pill (Preserves search if present) -->
                    <a
                        href="{{ route('shop.index', array_filter(['search' => $search])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ empty($selectedCategory) ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}"
                    >
                        <i class="fa-solid fa-border-all mr-1.5 text-[10px]"></i>
                        All Categories
                    </a>

                    <!-- Specific Category Pills (Preserves search if present) -->
                    @foreach ($categories as $cat)
                        <a
                            href="{{ route('shop.index', array_filter(['category' => $cat, 'search' => $search])) }}"
                            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $selectedCategory === $cat ? 'bg-brand-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900' }}"
                        >
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Results Meta & Active Filters Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white px-5 py-3.5 rounded-2xl border border-slate-200/80 mb-6 text-xs text-slate-600">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>
                        Showing <strong>{{ $products->firstItem() ?? 0 }}</strong> to <strong>{{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong> products
                    </span>

                    <!-- Active Filter Chips -->
                    @if(!empty($selectedCategory))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand-50 text-brand-700 rounded-lg text-xs font-medium border border-brand-200">
                            <span>Category: {{ $selectedCategory }}</span>
                            <a href="{{ route('shop.index', array_filter(['search' => $search])) }}" class="hover:text-rose-600 ml-0.5" title="Remove category filter">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                            </a>
                        </span>
                    @endif

                    @if(!empty($search))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-800 rounded-lg text-xs font-medium border border-amber-200">
                            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                            <span>Search: "{{ $search }}"</span>
                            <a href="{{ route('shop.index', array_filter(['category' => $selectedCategory])) }}" class="hover:text-rose-600 ml-0.5" title="Remove search filter">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                            </a>
                        </span>
                    @endif
                </div>

                <div class="text-slate-400 shrink-0">
                    Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="group bg-white rounded-2xl border border-slate-200/90 hover:border-slate-300 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden relative">

                        <!-- Product Image Area (Clickable to Details Page) -->
                        <div class="relative aspect-square overflow-hidden bg-slate-100 block">
                            <a href="{{ route('product.show', $product->id) }}" class="block w-full h-full">
                                <img
                                    src="{{ $product->image }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-108 transition-transform duration-500"
                                    loading="lazy"
                                >
                            </a>

                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start pointer-events-none">
                                @if (!empty($product->badge))
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold text-white shadow-sm {{ $product->badge_color }}">
                                        {{ $product->badge }}
                                    </span>
                                @endif
                                @if (!empty($product->discount_percent))
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">
                                        -{{ $product->discount_percent }}%
                                    </span>
                                @endif
                            </div>

                            @php
                                $inWishlist = in_array($product->id, session('wishlist', []), true);
                            @endphp

                            <!-- Wishlist Action Toggle -->
                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
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
                                        {{ $product->category }}
                                    </a>
                                    @php
                                        $realReviewsCount = $product->approved_reviews_count ?? ($product->relationLoaded('reviews') ? $product->reviews->where('status', 'approved')->count() : null);
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
                                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-2 leading-snug" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                @if(!empty($product->description))
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $product->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Pricing & Action Button -->
                            <div class="pt-4 mt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                                <div>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-lg font-extrabold text-slate-900">${{ number_format((float)$product->price, 2) }}</span>
                                        @if(!empty($product->original_price))
                                            <span class="text-xs text-slate-400 line-through">${{ number_format((float)$product->original_price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if($product->stock > 0)
                                        <span class="text-[10px] text-emerald-600 font-semibold block">In Stock ({{ $product->stock }})</span>
                                    @else
                                        <span class="text-[10px] text-rose-500 font-semibold block">Out of Stock</span>
                                    @endif
                                </div>

                                <!-- Add to Cart Button (Functional Phase C) -->
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
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
                    <!-- Empty State -->
                    <div class="col-span-full py-16 px-6 text-center bg-white border border-slate-200 rounded-3xl shadow-sm">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl shadow-sm">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No Matching Products Found</h3>
                        <p class="text-sm text-slate-500 max-w-md mx-auto mt-1 mb-5">
                            @if(!empty($search) && !empty($selectedCategory))
                                We couldn't find any products matching "{{ $search }}" in the category "{{ $selectedCategory }}".
                            @elseif(!empty($search))
                                We couldn't find any products matching your search term "{{ $search }}".
                            @elseif(!empty($selectedCategory))
                                No active products found in the category "{{ $selectedCategory }}".
                            @else
                                No products are currently available in the catalog.
                            @endif
                        </p>
                        <a
                            href="{{ route('shop.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors"
                        >
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Reset Filters &amp; View All</span>
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Bar (Preserves search & category parameters) -->
            @if ($products->hasPages())
                <div class="mt-12 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500">
                        Showing <span class="font-bold text-slate-700">{{ $products->firstItem() }}</span> to <span class="font-bold text-slate-700">{{ $products->lastItem() }}</span> of <span class="font-bold text-slate-700">{{ $products->total() }}</span> results
                    </div>

                    <div class="flex items-center gap-1">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <span class="px-3 py-2 text-xs font-semibold text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left mr-1"></i> Previous
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors">
                                <i class="fa-solid fa-chevron-left mr-1"></i> Previous
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
                                <span class="px-3.5 py-2 text-xs font-bold text-white bg-brand-600 rounded-lg shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors">
                                Next <i class="fa-solid fa-chevron-right ml-1"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-xs font-semibold text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                Next <i class="fa-solid fa-chevron-right ml-1"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
