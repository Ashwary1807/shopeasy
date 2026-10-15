@extends('layouts.app')

@section('title', 'My Wishlist - ShopEasy')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                    <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-slate-900 font-bold">Wishlist</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    <span>My Wishlist</span>
                    <span class="text-xs sm:text-sm font-bold px-3 py-1 bg-brand-50 text-brand-700 rounded-full border border-brand-200">
                        {{ $products->count() }} {{ Str::plural('Item', $products->count()) }}
                    </span>
                </h1>
            </div>

            @if ($products->isNotEmpty())
                <form action="{{ route('wishlist.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire wishlist?');">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="px-4 py-2 bg-white border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-2"
                    >
                        <i class="fa-regular fa-trash-can text-xs"></i>
                        <span>Clear Wishlist</span>
                    </button>
                </form>
            @endif
        </div>

        @if ($products->isEmpty())
            <!-- Empty Wishlist State -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-12 sm:p-16 text-center max-w-xl mx-auto">
                <div class="w-20 h-20 rounded-3xl bg-rose-50 text-rose-500 flex items-center justify-center text-3xl mx-auto mb-5 shadow-sm">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mb-2">
                    Your Wishlist is Empty
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-8 max-w-sm mx-auto">
                    You haven't saved any items yet. Explore our shop catalog and tap the heart icon on any product you love!
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a
                        href="{{ route('shop.index') }}"
                        class="w-full sm:w-auto px-6 py-3 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-bold rounded-xl transition-all shadow-sm inline-flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-bag-shopping text-xs"></i>
                        <span>Explore Catalog</span>
                    </a>
                    <a
                        href="{{ route('home') }}"
                        class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl transition-colors inline-flex items-center justify-center"
                    >
                        Back to Home
                    </a>
                </div>
            </div>
        @else
            <!-- Wishlist Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="group bg-white rounded-2xl border border-slate-200/90 hover:border-slate-300 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden relative">

                        <!-- Thumbnail & Badge -->
                        <div class="relative aspect-square overflow-hidden bg-slate-100 block">
                            <a href="{{ route('product.show', $product->id) }}" class="block w-full h-full">
                                <img
                                    src="{{ $product->image }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
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

                            <!-- Remove from Wishlist Button -->
                            <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" class="absolute top-3 right-3">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="w-9 h-9 rounded-full bg-white/95 hover:bg-rose-50 text-rose-500 hover:text-rose-600 shadow-sm flex items-center justify-center transition-all hover:scale-110"
                                    title="Remove from Wishlist"
                                >
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Product Content Area -->
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <span class="text-brand-600 font-semibold tracking-wide uppercase text-[10px]">
                                        {{ $product->category }}
                                    </span>
                                    <div class="flex items-center gap-1 text-amber-500 font-semibold text-[11px]">
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                        <span>{{ number_format((float)$product->rating, 1) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('product.show', $product->id) }}">
                                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-2 leading-snug" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </h3>
                                </a>
                            </div>

                            <!-- Pricing & Cart Button -->
                            <div class="pt-4 mt-3 border-t border-slate-100 flex items-center justify-between gap-2">
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
                                        class="px-3 py-2 bg-slate-100 text-slate-400 text-xs font-semibold rounded-xl cursor-not-allowed"
                                    >
                                        Unavailable
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
