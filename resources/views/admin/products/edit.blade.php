@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name . ' - ShopEasy Admin')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.products.index') }}" class="hover:text-brand-600 transition-colors">Products</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-900 font-bold truncate max-w-xs">{{ $product->name }}</span>
            </nav>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('product.show', $product->id) }}"
                    target="_blank"
                    class="px-3.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5 shadow-sm"
                    title="View how this product looks on storefront"
                >
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>Storefront View</span>
                </a>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition-colors inline-flex items-center gap-1.5 shadow-sm"
                >
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back to Products</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Edit Product #{{ $product->id }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Update specifications, pricing, stock quantity, or promotional badges.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                        Currently: {{ ucfirst($product->status) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $product->stock > 0 ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
                        Stock: {{ $product->stock }} units
                    </span>
                </div>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Basic Information</h2>

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Product Name <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $product->name) }}"
                            class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                            required
                        >
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Category <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                list="category_list"
                                id="category"
                                name="category"
                                value="{{ old('category', $product->category) }}"
                                class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                                required
                            >
                            <datalist id="category_list">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('category')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Catalog Status <span class="text-rose-500">*</span>
                            </label>
                            <select
                                id="status"
                                name="status"
                                class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-semibold"
                                required
                            >
                                <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active (Visible on Storefront)</option>
                                <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive (Hidden from Storefront)</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Description
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                        >{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing & Stock Inventory -->
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Pricing &amp; Inventory</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="price" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Selling Price ($) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    id="price"
                                    name="price"
                                    value="{{ old('price', $product->price) }}"
                                    class="w-full pl-8 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-bold"
                                    required
                                >
                            </div>
                            @error('price')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="original_price" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Original Price ($) <span class="text-slate-400 font-normal">(Strikethrough)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    id="original_price"
                                    name="original_price"
                                    value="{{ old('original_price', $product->original_price) }}"
                                    class="w-full pl-8 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                                >
                            </div>
                            @error('original_price')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Stock Quantity <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                step="1"
                                min="0"
                                id="stock"
                                name="stock"
                                value="{{ old('stock', $product->stock) }}"
                                class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-bold"
                                required
                            >
                            @error('stock')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Media & Image -->
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Product Image</h2>

                    <!-- Current Image Preview -->
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-white border border-slate-200 shrink-0 shadow-sm">
                            <img
                                src="{{ $product->image }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                                onerror="this.src='https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80'"
                            >
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-700 block mb-0.5">Current Active Image</span>
                            <span class="text-[11px] text-slate-400 font-mono truncate block max-w-md" title="{{ $product->image }}">
                                {{ $product->image }}
                            </span>
                            <span class="text-[10px] text-emerald-600 font-semibold mt-1 inline-block">
                                <i class="fa-solid fa-check mr-1"></i>Leave fields below blank to keep this image.
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Option A: Replace with External URL -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <label for="image_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Replace with New Image URL
                            </label>
                            <input
                                type="url"
                                id="image_url"
                                name="image_url"
                                value="{{ old('image_url') }}"
                                placeholder="https://images.unsplash.com/..."
                                class="w-full px-3.5 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium"
                            >
                            @error('image_url')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Option B: Replace with Local Upload -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <label for="image_file" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Replace with Uploaded File
                            </label>
                            <input
                                type="file"
                                id="image_file"
                                name="image_file"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100"
                            >
                            @error('image_file')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Promotional Badges -->
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Promotional Badge (Optional)</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="badge" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Badge Tag
                            </label>
                            <select
                                id="badge"
                                name="badge"
                                class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-semibold"
                            >
                                <option value="">No Badge</option>
                                <option value="Best Seller" {{ old('badge', $product->badge) === 'Best Seller' ? 'selected' : '' }}>Best Seller</option>
                                <option value="Featured" {{ old('badge', $product->badge) === 'Featured' ? 'selected' : '' }}>Featured</option>
                                <option value="Hot Deal" {{ old('badge', $product->badge) === 'Hot Deal' ? 'selected' : '' }}>Hot Deal</option>
                                <option value="New Arrival" {{ old('badge', $product->badge) === 'New Arrival' ? 'selected' : '' }}>New Arrival</option>
                                <option value="Popular" {{ old('badge', $product->badge) === 'Popular' ? 'selected' : '' }}>Popular</option>
                                <option value="Sale" {{ old('badge', $product->badge) === 'Sale' ? 'selected' : '' }}>Sale</option>
                                <option value="Top Rated" {{ old('badge', $product->badge) === 'Top Rated' ? 'selected' : '' }}>Top Rated</option>
                            </select>
                            @error('badge')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="badge_color" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Badge Color Class
                            </label>
                            <select
                                id="badge_color"
                                name="badge_color"
                                class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-semibold"
                            >
                                <option value="">Auto Assign Color</option>
                                <option value="bg-amber-500" {{ old('badge_color', $product->badge_color) === 'bg-amber-500' ? 'selected' : '' }}>Amber / Gold (bg-amber-500)</option>
                                <option value="bg-emerald-600" {{ old('badge_color', $product->badge_color) === 'bg-emerald-600' ? 'selected' : '' }}>Emerald Green (bg-emerald-600)</option>
                                <option value="bg-rose-500" {{ old('badge_color', $product->badge_color) === 'bg-rose-500' ? 'selected' : '' }}>Rose Red (bg-rose-500)</option>
                                <option value="bg-indigo-600" {{ old('badge_color', $product->badge_color) === 'bg-indigo-600' ? 'selected' : '' }}>Indigo (bg-indigo-600)</option>
                                <option value="bg-blue-600" {{ old('badge_color', $product->badge_color) === 'bg-blue-600' ? 'selected' : '' }}>Blue (bg-blue-600)</option>
                            </select>
                            @error('badge_color')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Action Buttons -->
                <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm rounded-xl transition-colors"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-sm flex items-center gap-2"
                    >
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Update Product</span>
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection
