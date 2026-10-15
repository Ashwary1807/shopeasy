@extends('layouts.app')

@section('title', $product->name . ' - ShopEasy')

@section('content')

    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs Navigation -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-8 font-medium overflow-x-auto whitespace-nowrap pb-1">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('shop.index') }}" class="hover:text-brand-600 transition-colors">Shop</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="hover:text-brand-600 transition-colors">
                    {{ $product->category }}
                </a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-800 font-bold truncate max-w-xs">{{ $product->name }}</span>
            </nav>

            <!-- Product Showcase Box -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden p-6 sm:p-10 mb-14">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">

                    <!-- Left: Product Image Area -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="relative aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200/80 group">
                            <img
                                src="{{ $product->image }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                            >

                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2 items-start">
                                @if (!empty($product->badge))
                                    <span class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm {{ $product->badge_color }}">
                                        {{ $product->badge }}
                                    </span>
                                @endif
                                @if (!empty($product->discount_percent))
                                    <span class="px-2.5 py-1 rounded-md text-xs font-extrabold bg-rose-100 text-rose-700 border border-rose-200 shadow-sm">
                                        -{{ $product->discount_percent }}% OFF
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Back to Catalog Link -->
                        <div class="pt-2 text-center sm:text-left">
                            <a
                                href="{{ route('shop.index') }}"
                                class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-brand-600 transition-colors"
                            >
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                                <span>Back to Full Catalog</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Product Details & Purchase Actions -->
                    <div class="lg:col-span-7 space-y-6">

                        <div>
                            <!-- Category and Stock Badge -->
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <a
                                    href="{{ route('shop.index', ['category' => $product->category]) }}"
                                    class="text-xs uppercase tracking-widest font-extrabold text-brand-600 bg-brand-50 hover:bg-brand-100 px-3 py-1 rounded-lg transition-colors"
                                >
                                    {{ $product->category }}
                                </a>

                                @if($product->stock > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        In Stock ({{ $product->stock }} units)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-semibold border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Out of Stock
                                    </span>
                                @endif
                            </div>

                            <!-- Product Title -->
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                                {{ $product->name }}
                            </h1>

                            <!-- Ratings & Review Count -->
                            <div class="flex items-center gap-3 mt-3 text-sm">
                                @if(isset($reviews) && $reviews->count() > 0)
                                    <div class="flex items-center gap-1 text-amber-500">
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <span class="font-bold text-slate-800 text-sm ml-0.5">{{ number_format((float)$product->rating, 1) }}</span>
                                    </div>
                                    <span class="text-slate-300">|</span>
                                    <a href="#customer-reviews" class="text-slate-500 hover:text-brand-600 transition-colors text-xs font-medium">
                                        Based on {{ $reviews->count() }} verified {{ \Illuminate\Support\Str::plural('review', $reviews->count()) }}
                                    </a>
                                    <span class="text-slate-300">|</span>
                                    <span class="text-emerald-600 text-xs font-semibold"><i class="fa-solid fa-check mr-1"></i>Verified Authentic</span>
                                @else
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200/80 font-medium">
                                            <i class="fa-regular fa-star text-[11px] text-amber-600"></i>
                                            <span>No reviews yet &mdash; be the first to review this product!</span>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-100 flex flex-wrap items-baseline gap-3">
                            <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                                ${{ number_format((float)$product->price, 2) }}
                            </span>

                            @if(!empty($product->original_price))
                                <span class="text-base sm:text-lg text-slate-400 line-through">
                                    ${{ number_format((float)$product->original_price, 2) }}
                                </span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                                    Save ${{ number_format((float)($product->original_price - $product->price), 2) }}
                                </span>
                            @endif
                        </div>

                        @php
                            $eligiblePromoCategories = ['Audio & Gadgets', 'Electronics', 'Watches & Accessories'];
                        @endphp
                        @if(in_array($product->category, $eligiblePromoCategories, true))
                            <!-- Applicable Coupon Offer Callout -->
                            <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 flex items-start sm:items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-start sm:items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center text-sm shrink-0 shadow-xs">
                                        <i class="fa-solid fa-tag"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-bold text-slate-900">Special Promo Offer</span>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-extrabold bg-brand-100 text-brand-700 border border-brand-200">SHOPEASY50</span>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">50% OFF</span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-1">
                                            Eligible for <strong>50% off</strong> with code <span class="font-mono font-bold text-brand-700">SHOPEASY50</span> (Min. $30.00 subtotal, max $25.00 discount).
                                        </p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    onclick="copyPromoCode('SHOPEASY50')"
                                    class="px-3 py-1.5 bg-white hover:bg-slate-50 text-brand-700 border border-brand-200 hover:border-brand-300 text-xs font-bold rounded-xl shadow-2xs transition-all inline-flex items-center gap-1.5 shrink-0 self-center cursor-pointer"
                                    title="Copy coupon code"
                                >
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>Copy</span>
                                </button>
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="space-y-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">About this product</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $product->description ?? 'No description available for this product.' }}
                            </p>
                        </div>

                        <!-- Purchase & Interaction Block (Functional Phase C & Wishlist) -->
                        <div class="pt-4 border-t border-slate-100 space-y-4">
                            @php
                                $inWishlist = in_array($product->id, session('wishlist', []), true);
                            @endphp

                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:flex-1">
                                        @csrf
                                        <!-- Quantity Stepper -->
                                        <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 p-1 w-full sm:w-auto justify-between sm:justify-start">
                                            <button
                                                type="button"
                                                onclick="const q = document.getElementById('productQtyInput'); const v = parseInt(q.value) || 1; if(v > 1) q.value = v - 1;"
                                                class="w-9 h-9 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-white rounded-lg transition-colors"
                                                title="Decrease quantity"
                                            >
                                                <i class="fa-solid fa-minus text-xs"></i>
                                            </button>
                                            <input
                                                type="number"
                                                id="productQtyInput"
                                                name="quantity"
                                                value="1"
                                                min="1"
                                                max="{{ $product->stock }}"
                                                class="w-12 text-center font-bold text-sm text-slate-800 bg-transparent border-0 focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                readonly
                                            >
                                            <button
                                                type="button"
                                                onclick="const q = document.getElementById('productQtyInput'); const v = parseInt(q.value) || 1; const max = parseInt('{{ $product->stock }}') || 1; if(v < max) q.value = v + 1;"
                                                class="w-9 h-9 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-white rounded-lg transition-colors"
                                                title="Increase quantity"
                                            >
                                                <i class="fa-solid fa-plus text-xs"></i>
                                            </button>
                                        </div>

                                        <!-- Add to Cart Button -->
                                        <button
                                            type="submit"
                                            class="w-full sm:flex-1 py-3.5 px-6 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow transition-all inline-flex items-center justify-center gap-2 group"
                                        >
                                            <i class="fa-solid fa-cart-shopping text-sm group-hover:scale-110 transition-transform"></i>
                                            <span>Add to Cart</span>
                                        </button>
                                    </form>
                                @else
                                    <button
                                        type="button"
                                        disabled
                                        class="w-full sm:flex-1 py-3.5 px-6 bg-slate-200 text-slate-400 font-semibold rounded-xl text-sm cursor-not-allowed inline-flex items-center justify-center gap-2"
                                    >
                                        <i class="fa-solid fa-ban text-sm"></i>
                                        <span>Out of Stock</span>
                                    </button>
                                @endif

                                <!-- Wishlist Toggle Button (Independent Form) -->
                                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="w-full sm:w-auto shrink-0">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full sm:w-auto p-3.5 border rounded-xl transition-all inline-flex items-center justify-center gap-2 {{ $inWishlist ? 'border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100' : 'border-slate-200 hover:border-slate-300 text-slate-600 hover:text-rose-500 hover:bg-rose-50' }}"
                                        title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                                    >
                                        <i class="{{ $inWishlist ? 'fa-solid text-rose-600' : 'fa-regular' }} fa-heart text-lg"></i>
                                        <span class="sm:hidden text-xs font-bold">{{ $inWishlist ? 'Wishlisted' : 'Add to Wishlist' }}</span>
                                    </button>
                                </form>
                            </div>

                            <p class="text-[11px] text-slate-400">
                                <i class="fa-solid fa-shield-halved mr-1 text-slate-400"></i>
                                Guaranteed safe checkout &amp; hassle-free 30-day returns.
                            </p>
                        </div>

                        <!-- Store Assurances Strip -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4 border-t border-slate-100 text-xs text-slate-600">
                            <div class="flex items-center gap-2.5 p-2 rounded-lg bg-slate-50">
                                <i class="fa-solid fa-truck-fast text-brand-600 text-sm"></i>
                                <span>Free shipping over $49</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-2 rounded-lg bg-slate-50">
                                <i class="fa-solid fa-rotate-left text-brand-600 text-sm"></i>
                                <span>30-Day easy returns</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-2 rounded-lg bg-slate-50">
                                <i class="fa-solid fa-shield-halved text-brand-600 text-sm"></i>
                                <span>100% Secure payment</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- CUSTOMER REVIEWS & RATINGS SECTION -->
            <div id="customer-reviews" class="mb-14 scroll-mt-8">

                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                    <div>
                        <span class="text-xs uppercase tracking-widest font-extrabold text-brand-600 block mb-1">Customer Feedback</span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                            Reviews &amp; Ratings
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Real ratings and written impressions from verified buyers.
                        </p>
                    </div>

                    @if(isset($canReview) && $canReview && !$userReview)
                        <a
                            href="#write-review-form"
                            class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors inline-flex items-center gap-2 self-start sm:self-auto"
                        >
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            <span>Write a Review</span>
                        </a>
                    @endif
                </div>

                <!-- 1. Rating Summary Breakdown Card -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8">
                    @if(isset($reviews) && $reviews->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                            <!-- Left: Overall Score -->
                            <div class="md:col-span-4 flex flex-col items-center justify-center text-center p-4 border-b md:border-b-0 md:border-r border-slate-100">
                                <div class="text-5xl font-black text-slate-900 tracking-tight">
                                    {{ number_format((float)$product->rating, 1) }}
                                </div>
                                <div class="flex items-center gap-1 text-amber-400 text-base my-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round((float)$product->rating))
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star text-slate-300"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-xs font-semibold text-slate-500">
                                    Based on {{ $reviews->count() }} verified {{ \Illuminate\Support\Str::plural('review', $reviews->count()) }}
                                </p>
                                <span class="mt-2 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-shield-halved text-[9px]"></i> 100% Verified Purchases
                                </span>
                            </div>

                            <!-- Right: Star Breakdown Progress Bars -->
                            <div class="md:col-span-8 space-y-2.5 max-w-lg mx-auto w-full">
                                @for($star = 5; $star >= 1; $star--)
                                    @php
                                        $starCount = $ratingBreakdown[$star] ?? 0;
                                        $pct = $reviews->count() > 0 ? round(($starCount / $reviews->count()) * 100) : 0;
                                    @endphp
                                    <div class="flex items-center gap-3 text-xs">
                                        <div class="flex items-center gap-1 w-12 text-slate-700 font-semibold shrink-0">
                                            <span>{{ $star }}</span>
                                            <i class="fa-solid fa-star text-[10px] text-amber-400"></i>
                                        </div>
                                        <div class="flex-1 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div
                                                class="h-full bg-amber-400 rounded-full transition-all duration-500"
                                                style="width: {{ $pct }}%"
                                            ></div>
                                        </div>
                                        <div class="w-16 text-right text-slate-400 text-[11px] shrink-0 font-medium">
                                            {{ $starCount }} ({{ $pct }}%)
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @else
                        <!-- Zero Reviews Empty State in Summary Card -->
                        <div class="text-center py-8 max-w-md mx-auto">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 mx-auto flex items-center justify-center text-2xl mb-3">
                                <i class="fa-regular fa-star"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">No reviews yet</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 mb-4">
                                Be the first verified buyer to share your feedback for <strong>{{ $product->name }}</strong>!
                            </p>
                            @auth
                                @if(isset($canReview) && !$canReview)
                                    <p class="text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                                        <i class="fa-solid fa-circle-info mr-1 text-brand-600"></i>
                                        Purchased this item? You can leave a review once your delivery is completed.
                                    </p>
                                @endif
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-brand-600 text-white font-semibold rounded-xl text-xs transition-colors"
                                >
                                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                                    <span>Log in to Write a Review</span>
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- 2. Customer Action / Review Submission Card -->
                <div class="space-y-6">
                    @auth
                        @if(isset($userReview) && $userReview)
                            <!-- User Already Reviewed Card with Edit / Delete Controls -->
                            <div class="bg-brand-50/40 border border-brand-200/80 rounded-3xl p-6 sm:p-8 shadow-xs">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-brand-200/60">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-brand-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-sm font-bold text-slate-900">Your Review</h3>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    <i class="fa-solid fa-circle-check text-[9px]"></i> Verified Buyer
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                Reviewed on {{ $userReview->created_at->format('M d, Y') }}
                                                @if($userReview->updated_at && $userReview->updated_at->gt($userReview->created_at))
                                                    <span class="italic text-slate-400">(Edited)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- Toggle Edit Button -->
                                        <button
                                            type="button"
                                            onclick="const f = document.getElementById('edit-review-form'); f.classList.toggle('hidden');"
                                            class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5 shadow-xs"
                                        >
                                            <i class="fa-solid fa-pen text-[10px] text-slate-500"></i>
                                            <span>Edit Review</span>
                                        </button>

                                        <!-- Delete Review Form -->
                                        <form
                                            action="{{ route('reviews.destroy', $userReview->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete your review? This action cannot be undone.');"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5 shadow-xs"
                                            >
                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Current Review Preview -->
                                <div class="pt-4">
                                    <div class="flex items-center gap-1 text-amber-400 text-xs mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $userReview->rating)
                                                <i class="fa-solid fa-star"></i>
                                            @else
                                                <i class="fa-regular fa-star text-slate-300"></i>
                                            @endif
                                        @endfor
                                        <span class="ml-1 font-bold text-slate-700">{{ $userReview->rating }}.0 / 5.0</span>
                                    </div>

                                    @if(!empty($userReview->title))
                                        <h4 class="text-sm font-extrabold text-slate-900 mb-1">
                                            {{ $userReview->title }}
                                        </h4>
                                    @endif

                                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                        {{ $userReview->comment }}
                                    </p>
                                </div>

                                <!-- Inline Edit Form (Hidden by default) -->
                                <div id="edit-review-form" class="hidden mt-6 pt-6 border-t border-brand-200/80">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">Edit Your Review</h4>

                                    <form action="{{ route('reviews.update', $userReview->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <!-- Rating Selector -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                                Rating <span class="text-rose-500">*</span>
                                            </label>
                                            <div class="flex items-center gap-3">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <label class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white border border-transparent hover:border-slate-200 transition-colors">
                                                        <input
                                                            type="radio"
                                                            name="rating"
                                                            value="{{ $i }}"
                                                            {{ old('rating', $userReview->rating) == $i ? 'checked' : '' }}
                                                            class="text-amber-500 focus:ring-amber-400"
                                                            required
                                                        >
                                                        <span>{{ $i }} <i class="fa-solid fa-star text-[10px] text-amber-400"></i></span>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <!-- Title Input -->
                                        <div>
                                            <label for="edit_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                                Review Headline (Optional)
                                            </label>
                                            <input
                                                type="text"
                                                id="edit_title"
                                                name="title"
                                                value="{{ old('title', $userReview->title) }}"
                                                maxlength="150"
                                                placeholder="e.g. Excellent build quality, highly recommend!"
                                                class="w-full px-4 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800"
                                            >
                                        </div>

                                        <!-- Comment Input -->
                                        <div>
                                            <label for="edit_comment" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                                Your Review <span class="text-rose-500">*</span>
                                            </label>
                                            <textarea
                                                id="edit_comment"
                                                name="comment"
                                                rows="4"
                                                required
                                                minlength="5"
                                                maxlength="2000"
                                                placeholder="Share details of your experience with this product..."
                                                class="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800"
                                            >{{ old('comment', $userReview->comment) }}</textarea>
                                            <p class="text-[11px] text-slate-400 mt-1">Minimum 5 characters, maximum 2000 characters.</p>
                                        </div>

                                        <div class="flex items-center gap-2 pt-2">
                                            <button
                                                type="submit"
                                                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors"
                                            >
                                                Update Review
                                            </button>
                                            <button
                                                type="button"
                                                onclick="document.getElementById('edit-review-form').classList.add('hidden')"
                                                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition-colors"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @elseif(isset($canReview) && $canReview)
                            <!-- Review Submission Form for Eligible Verified Buyer -->
                            <div id="write-review-form" class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                                    <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shrink-0">
                                        <i class="fa-solid fa-pen-fancy"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-base font-bold text-slate-900">Write a Customer Review</h3>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="fa-solid fa-circle-check text-[9px]"></i> Verified Buyer
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            You purchased this product in a completed order. Share your honest experience with other shoppers.
                                        </p>
                                    </div>
                                </div>

                                <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="space-y-4 max-w-2xl">
                                    @csrf

                                    <!-- Rating Selector -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                            Overall Rating <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                                            @for($i = 5; $i >= 1; $i--)
                                                <label class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-700 cursor-pointer p-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="{{ $i }}"
                                                        {{ old('rating', '5') == $i ? 'checked' : '' }}
                                                        class="text-amber-500 focus:ring-amber-400"
                                                        required
                                                    >
                                                    <span>{{ $i }} <i class="fa-solid fa-star text-[10px] text-amber-400"></i></span>
                                                </label>
                                            @endfor
                                        </div>
                                        @error('rating')
                                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Headline / Title -->
                                    <div>
                                        <label for="review_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Review Headline (Optional)
                                        </label>
                                        <input
                                            type="text"
                                            id="review_title"
                                            name="title"
                                            value="{{ old('title') }}"
                                            maxlength="150"
                                            placeholder="What's most important to know? (e.g. Fantastic battery life!)"
                                            class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('title') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all"
                                        >
                                        @error('title')
                                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Written Comment -->
                                    <div>
                                        <label for="review_comment" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Written Review <span class="text-rose-500">*</span>
                                        </label>
                                        <textarea
                                            id="review_comment"
                                            name="comment"
                                            rows="4"
                                            required
                                            minlength="5"
                                            maxlength="2000"
                                            placeholder="What did you like or dislike? What was the fit, quality, or performance like?"
                                            class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('comment') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all"
                                        >{{ old('comment') }}</textarea>
                                        <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1">
                                            <span>Minimum 5 characters.</span>
                                            <span>Max 2000 characters.</span>
                                        </div>
                                        @error('comment')
                                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="pt-2">
                                        <button
                                            type="submit"
                                            class="px-6 py-3 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-xs shadow-md hover:shadow transition-all inline-flex items-center gap-2"
                                        >
                                            <i class="fa-solid fa-paper-plane text-xs"></i>
                                            <span>Submit Verified Review</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <!-- Logged In Customer but Has Not Purchased / Received Product -->
                            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 text-xs text-slate-600 flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-sm shrink-0 mt-0.5">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-0.5">Verified Buyers Only</h4>
                                    <p class="text-slate-500 leading-relaxed">
                                        To maintain genuine review integrity on ShopEasy, reviews can only be submitted by customers who have purchased and received this product (Order status: Delivered). Once your order is delivered, you will be invited to leave a review!
                                    </p>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Guest Callout -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 text-xs text-slate-600 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-base shrink-0">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Have you purchased this product?</h4>
                                    <p class="text-slate-500">Log in to your ShopEasy account to submit your verified customer review.</p>
                                </div>
                            </div>
                            <a
                                href="{{ route('login') }}"
                                class="px-5 py-2.5 bg-slate-900 hover:bg-brand-600 text-white font-semibold rounded-xl text-xs transition-colors inline-flex items-center gap-1.5 self-start sm:self-auto shrink-0 shadow-sm"
                            >
                                <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                                <span>Log In to Review</span>
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- 3. Approved Reviews Feed -->
                @if(isset($reviews) && $reviews->count() > 0)
                    <div class="space-y-4 pt-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">
                            Customer Reviews ({{ $reviews->count() }})
                        </h3>

                        <div class="space-y-4">
                            @foreach($reviews as $rev)
                                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-7 space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ strtoupper(substr($rev->user->name ?? 'C', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-slate-900">{{ $rev->user->name ?? 'Verified Customer' }}</span>
                                                    @if($rev->is_verified_purchase)
                                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                                            <i class="fa-solid fa-check text-[8px]"></i> Verified Buyer
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-slate-400 block mt-0.5">
                                                    {{ $rev->created_at ? $rev->created_at->format('M d, Y') : '' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Rating Stars -->
                                        <div class="flex items-center gap-1 text-amber-400 text-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rev->rating)
                                                    <i class="fa-solid fa-star"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-slate-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>

                                    @if(!empty($rev->title))
                                        <h4 class="text-sm font-bold text-slate-900 pt-1">
                                            {{ $rev->title }}
                                        </h4>
                                    @endif

                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                        {{ $rev->comment }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Related Products Section (Excludes current product) -->
            @if($relatedProducts->isNotEmpty())
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs uppercase tracking-widest font-extrabold text-brand-600 block mb-1">Recommended</span>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                                Similar Products in {{ $product->category }}
                            </h2>
                        </div>
                        <a
                            href="{{ route('shop.index', ['category' => $product->category]) }}"
                            class="text-xs font-semibold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1 transition-colors"
                        >
                            <span>View All {{ $product->category }}</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $rel)
                            <div class="group bg-white rounded-2xl border border-slate-200/90 hover:border-slate-300 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden relative">

                                <!-- Image Link -->
                                <a href="{{ route('product.show', $rel->id) }}" class="relative aspect-square overflow-hidden bg-slate-100 block">
                                    <img
                                        src="{{ $rel->image }}"
                                        alt="{{ $rel->name }}"
                                        class="w-full h-full object-cover object-center group-hover:scale-108 transition-transform duration-500"
                                        loading="lazy"
                                    >
                                    @if (!empty($rel->badge))
                                        <span class="absolute top-3 left-3 px-2 py-0.5 rounded-full text-[10px] font-bold text-white shadow-sm {{ $rel->badge_color }}">
                                            {{ $rel->badge }}
                                        </span>
                                    @endif
                                </a>

                                <div class="p-4 flex-grow flex flex-col justify-between">
                                    <div>
                                        <span class="text-slate-400 font-medium tracking-wide uppercase text-[10px] block mb-1">
                                            {{ $rel->category }}
                                        </span>
                                        <a href="{{ route('product.show', $rel->id) }}">
                                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1 leading-snug">
                                                {{ $rel->name }}
                                            </h3>
                                        </a>
                                    </div>

                                    <div class="pt-3 mt-2 border-t border-slate-100 flex items-center justify-between">
                                        <span class="text-base font-extrabold text-slate-900">${{ number_format((float)$rel->price, 2) }}</span>
                                        <a
                                            href="{{ route('product.show', $rel->id) }}"
                                            class="text-xs font-semibold text-brand-600 hover:text-brand-700"
                                        >
                                            View &rarr;
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
