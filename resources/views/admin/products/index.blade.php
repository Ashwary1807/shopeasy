@extends('layouts.admin')

@section('title', 'Products & Inventory Management - ShopEasy Admin')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header & Action Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Products &amp; Inventory Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Manage catalog offerings, update stock levels, toggle visibility, and monitor product inventory health.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.products.create') }}"
                    class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-sm flex items-center gap-2 group"
                >
                    <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform"></i>
                    <span>Add New Product</span>
                </a>
            </div>
        </div>

        <!-- 1. SUMMARY METRIC COUNTER CARDS -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-8">

            <!-- Total Products -->
            <a href="{{ route('admin.products.index') }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-brand-300 hover:shadow transition-all group">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1 group-hover:text-brand-600">Total Catalog</span>
                <span class="text-2xl font-black text-slate-900 block">{{ $metrics['total'] }}</span>
            </a>

            <!-- Active Products -->
            <a href="{{ route('admin.products.index', ['status' => 'active']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-emerald-300 hover:shadow transition-all group {{ $selectedStatus === 'active' ? 'ring-2 ring-emerald-500/20 border-emerald-300 bg-emerald-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 block mb-1">Active (Live)</span>
                <span class="text-2xl font-black text-emerald-700 block">{{ $metrics['active'] }}</span>
            </a>

            <!-- Inactive Products -->
            <a href="{{ route('admin.products.index', ['status' => 'inactive']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-slate-300 hover:shadow transition-all group {{ $selectedStatus === 'inactive' ? 'ring-2 ring-slate-500/20 border-slate-300 bg-slate-50' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Inactive (Hidden)</span>
                <span class="text-2xl font-black text-slate-700 block">{{ $metrics['inactive'] }}</span>
            </a>

            <!-- Low Stock Warning (1 - 15) -->
            <a href="{{ route('admin.products.index', ['status' => 'low_stock']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-amber-300 hover:shadow transition-all group {{ $selectedStatus === 'low_stock' ? 'ring-2 ring-amber-500/20 border-amber-300 bg-amber-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 block mb-1">Low Stock (1-15)</span>
                <span class="text-2xl font-black text-amber-700 block">{{ $metrics['low_stock'] }}</span>
            </a>

            <!-- Out of Stock (0) -->
            <a href="{{ route('admin.products.index', ['status' => 'out_of_stock']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:border-rose-300 hover:shadow transition-all group {{ $selectedStatus === 'out_of_stock' ? 'ring-2 ring-rose-500/20 border-rose-300 bg-rose-50/20' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-500 block mb-1">Out of Stock (0)</span>
                <span class="text-2xl font-black text-rose-600 block">{{ $metrics['out_of_stock'] }}</span>
            </a>

        </div>

        <!-- 2. SEARCH & FILTER TOOLBAR -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-4 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <!-- Status Filter Tabs -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-2 lg:pb-0">
                    @php
                        $filterTabs = [
                            'all' => 'All (' . $metrics['total'] . ')',
                            'active' => 'Active (' . $metrics['active'] . ')',
                            'inactive' => 'Inactive (' . $metrics['inactive'] . ')',
                            'low_stock' => 'Low Stock (' . $metrics['low_stock'] . ')',
                            'out_of_stock' => 'Out of Stock (' . $metrics['out_of_stock'] . ')',
                        ];
                    @endphp

                    @foreach ($filterTabs as $key => $label)
                        <a
                            href="{{ route('admin.products.index', array_filter(['status' => ($key === 'all' ? null : $key), 'category' => $selectedCategory, 'search' => $search])) }}"
                            class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap {{ ($selectedStatus === $key) ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <!-- Category & Search Form -->
                <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5 w-full lg:w-auto">
                    @if($selectedStatus !== 'all')
                        <input type="hidden" name="status" value="{{ $selectedStatus }}">
                    @endif

                    <!-- Category Filter Dropdown -->
                    <select
                        name="category"
                        onchange="this.form.submit()"
                        class="w-full sm:w-44 px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-700 font-semibold"
                    >
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-64">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search products..."
                            class="w-full pl-9 pr-9 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-800 font-medium placeholder-slate-400"
                        >
                        <i class="fa-solid fa-magnifying-glass text-xs text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        @if(!empty($search))
                            <a
                                href="{{ route('admin.products.index', array_filter(['status' => ($selectedStatus === 'all' ? null : $selectedStatus), 'category' => $selectedCategory])) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                title="Clear search"
                            >
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="w-full sm:w-auto px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-colors shadow-sm"
                    >
                        Search
                    </button>
                </form>

            </div>
        </div>

        <!-- 3. PRODUCTS DATA TABLE -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">

            @if ($products->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">No products found</h3>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto mb-6">
                        @if(!empty($search) || !empty($selectedCategory) || $selectedStatus !== 'all')
                            No products matched your active filters or search terms.
                        @else
                            No products are currently in your catalog.
                        @endif
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        @if(!empty($search) || !empty($selectedCategory) || $selectedStatus !== 'all')
                            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                                Reset Filters
                            </a>
                        @endif
                        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl transition-colors">
                            Add New Product
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                            <tr>
                                <th scope="col" class="py-4 px-4 sm:px-6">Product</th>
                                <th scope="col" class="py-4 px-4">Category</th>
                                <th scope="col" class="py-4 px-4">Price</th>
                                <th scope="col" class="py-4 px-4">Stock &amp; Inventory</th>
                                <th scope="col" class="py-4 px-4">Status</th>
                                <th scope="col" class="py-4 px-4 text-right pr-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($products as $product)
                                <tr class="hover:bg-slate-50/80 transition-colors">

                                    <!-- Product Info & Thumbnail -->
                                    <td class="py-4 px-4 sm:px-6">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                                <img
                                                    src="{{ $product->image }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-full h-full object-cover"
                                                    onerror="this.src='https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80'"
                                                >
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-slate-900 text-sm truncate max-w-xs block" title="{{ $product->name }}">
                                                        {{ $product->name }}
                                                    </span>
                                                    @if(!empty($product->badge))
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold text-white {{ $product->badge_color }}">
                                                            {{ $product->badge }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] text-slate-400 mt-0.5">
                                                    ID: #{{ $product->id }} &bull; Rating: {{ number_format((float)$product->rating, 1) }} ({{ $product->reviews_count }})
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td class="py-4 px-4 font-semibold text-slate-700 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs">
                                            {{ $product->category }}
                                        </span>
                                    </td>

                                    <!-- Price -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <div class="font-black text-slate-900 text-sm">
                                            ${{ number_format((float)$product->price, 2) }}
                                        </div>
                                        @if(!empty($product->original_price))
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-[11px] text-slate-400 line-through">
                                                    ${{ number_format((float)$product->original_price, 2) }}
                                                </span>
                                                @if(!empty($product->discount_percent))
                                                    <span class="text-[10px] font-extrabold px-1 rounded bg-rose-100 text-rose-700">
                                                        -{{ $product->discount_percent }}%
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Stock & Inventory -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            @if($product->stock <= 0)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    0 units (Out of Stock)
                                                </span>
                                            @elseif($product->stock <= 15)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    {{ $product->stock }} units (Low Stock)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    {{ $product->stock }} units in stock
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Quick Stock Adjustment Form -->
                                        <form action="{{ route('admin.products.stock', $product->id) }}" method="POST" class="inline-flex items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <input
                                                type="number"
                                                name="stock"
                                                value="{{ $product->stock }}"
                                                min="0"
                                                max="999999"
                                                class="w-16 px-2 py-1 text-xs bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-brand-500"
                                                title="Adjust inventory quantity"
                                            >
                                            <button
                                                type="submit"
                                                class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold rounded-lg transition-colors border border-slate-200"
                                                title="Save stock value"
                                            >
                                                Save
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Status & Quick Toggle -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <form action="{{ route('admin.products.status', $product->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $product->status === 'active' ? 'inactive' : 'active' }}">

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}"
                                                title="Click to toggle active/inactive status"
                                            >
                                                <span class="w-2 h-2 rounded-full {{ $product->status === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                <span>{{ ucfirst($product->status) }}</span>
                                                <i class="fa-solid fa-arrows-rotate text-[10px] ml-0.5 opacity-60"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-4 text-right pr-6 whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            <a
                                                href="{{ route('product.show', $product->id) }}"
                                                target="_blank"
                                                class="p-2 text-slate-400 hover:text-brand-600 hover:bg-slate-100 rounded-xl transition-colors"
                                                title="View on Storefront"
                                            >
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                            <a
                                                href="{{ route('admin.products.edit', $product->id) }}"
                                                class="px-3 py-1.5 bg-slate-900 hover:bg-brand-600 text-white font-bold text-xs rounded-xl transition-colors shadow-sm inline-flex items-center gap-1.5"
                                            >
                                                <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                                <span>Edit</span>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bar -->
                @if ($products->hasPages())
                    <div class="p-4 sm:p-6 border-t border-slate-100 bg-slate-50/50">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif

        </div>

    </div>

@endsection
