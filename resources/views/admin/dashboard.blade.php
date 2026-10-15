@extends('layouts.admin')

@section('title', 'Admin Dashboard Analytics - ShopEasy')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Top Header & Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span>Store Analytics Dashboard</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Live overview of customer orders, sales performance, fulfillment pipelines, and top merchandise.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Date Range Selector (Requirement 2) -->
                <div class="inline-flex rounded-xl bg-white border border-slate-200/90 p-1 shadow-sm">
                    <a
                        href="{{ route('admin.dashboard', ['range' => '7days']) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $range === '7days' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        7 Days
                    </a>
                    <a
                        href="{{ route('admin.dashboard', ['range' => '14days']) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $range === '14days' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        14 Days
                    </a>
                    <a
                        href="{{ route('admin.dashboard', ['range' => '30days']) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $range === '30days' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        30 Days
                    </a>
                </div>

                <!-- Database status badge -->
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-600 font-semibold shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="hidden sm:inline">Live Database</span>
                </span>

                <!-- Quick Link to Orders Management -->
                <a
                    href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm hover:shadow transition-all"
                >
                    <i class="fa-solid fa-box-open text-xs"></i>
                    <span>Orders Hub</span>
                </a>
            </div>
        </div>

        <!-- 1. SUMMARY KPI METRIC CARDS (Requirements 1, 5, 6) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">

            <!-- Card 1: Total Orders -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Orders</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
                <div>
                    <span class="text-3xl font-black text-slate-900 block leading-tight">{{ $totalOrders }}</span>
                    <span class="text-[11px] text-slate-500 font-medium mt-1 block">
                        {{ $totalOrders - $cancelledOrders }} non-cancelled
                    </span>
                </div>
            </div>

            <!-- Card 2: Total Customers (Safeguard 6) -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Customers</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div>
                    <span class="text-3xl font-black text-slate-900 block leading-tight">{{ $registeredCustomersCount }}</span>
                    <span class="text-[11px] text-slate-500 font-medium mt-1 block" title="{{ $uniqueOrderCustomersCount }} unique customer emails placed orders">
                        {{ $uniqueOrderCustomersCount }} ordering {{ Str::plural('purchaser', $uniqueOrderCustomersCount) }}
                    </span>
                </div>
            </div>

            <!-- Card 3: Pending Orders -->
            <a
                href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:border-amber-300 hover:shadow transition-all group flex flex-col justify-between"
            >
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-600">Pending</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <div>
                    <span class="text-3xl font-black text-amber-700 block leading-tight">{{ $pendingOrders }}</span>
                    <span class="text-[11px] text-slate-500 font-medium mt-1 block group-hover:text-amber-700">
                        Needs fulfillment &rarr;
                    </span>
                </div>
            </a>

            <!-- Card 4: Delivered Orders -->
            <a
                href="{{ route('admin.orders.index', ['status' => 'delivered']) }}"
                class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:border-emerald-300 hover:shadow transition-all group flex flex-col justify-between"
            >
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-600">Delivered</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div>
                    <span class="text-3xl font-black text-emerald-700 block leading-tight">{{ $deliveredOrders }}</span>
                    <span class="text-[11px] text-slate-500 font-medium mt-1 block group-hover:text-emerald-700">
                        Completed orders &rarr;
                    </span>
                </div>
            </a>

            <!-- Card 5: Cancelled Orders -->
            <a
                href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
                class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:border-rose-300 hover:shadow transition-all group flex flex-col justify-between"
            >
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-rose-500">Cancelled</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                </div>
                <div>
                    <span class="text-3xl font-black text-rose-600 block leading-tight">{{ $cancelledOrders }}</span>
                    <span class="text-[11px] text-slate-500 font-medium mt-1 block group-hover:text-rose-700">
                        Excluded from revenue
                    </span>
                </div>
            </a>

            <!-- Card 6: Total Revenue (Safeguards 3 & 4) -->
            <div class="p-5 rounded-3xl bg-gradient-to-br from-indigo-900 to-slate-900 text-white shadow-md flex flex-col justify-between relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-brand-300">Total Revenue (Paid)</span>
                    <div class="w-8 h-8 rounded-xl bg-brand-500/20 text-brand-300 flex items-center justify-center text-xs border border-brand-500/30">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <div>
                    <span class="text-3xl font-black text-white block leading-tight">${{ number_format($paidRevenue, 2) }}</span>
                    <div class="mt-2 pt-2 border-t border-slate-800 text-[10px] text-slate-300 space-y-0.5">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Pending COD:</span>
                            <span class="font-bold text-amber-300">${{ number_format($pendingCollection, 2) }}</span>
                        </div>
                        <div class="flex justify-between" title="Gross non-cancelled order sales volume">
                            <span class="text-slate-400">Sales Volume:</span>
                            <span class="font-bold text-slate-200">${{ number_format($totalSalesVolume, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 2. MAIN SECTION: SALES CHART & TOP SELLING PRODUCTS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">

            <!-- Sales Chart (8 Cols) -->
            <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Sales Trends (Last {{ $daysCount }} Days)</h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Daily order sales volume and paid revenue (excludes cancelled orders).
                            </p>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-semibold">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-indigo-600 inline-block"></span>
                                <span class="text-slate-600">Sales Volume (${{ number_format($rangeTotalVolume, 2) }})</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                                <span class="text-slate-600">Paid (${{ number_format($rangePaidRevenue, 2) }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Canvas Container -->
                    <div class="relative w-full h-72 sm:h-80">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>

                <!-- Fallback & Data Summary Pills -->
                <div class="grid grid-cols-3 gap-2 mt-6 pt-4 border-t border-slate-100 text-center">
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Orders in Window</span>
                        <span class="text-sm font-black text-slate-800">{{ $rangeTotalOrders }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Sales Volume</span>
                        <span class="text-sm font-black text-indigo-700">${{ number_format($rangeTotalVolume, 2) }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Paid Collected</span>
                        <span class="text-sm font-black text-emerald-700">${{ number_format($rangePaidRevenue, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products (4 Cols) (Safeguard 5) -->
            <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Top-Selling Products</h2>
                        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-800">
                            Products &rarr;
                        </a>
                    </div>
                    <p class="text-xs text-slate-500 mb-5">
                        Ranked by actual units sold in non-cancelled orders.
                    </p>

                    <div class="divide-y divide-slate-100">
                        @forelse($topSellingProducts as $index => $item)
                            <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <!-- Rank Pill -->
                                    <span class="w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black {{ $index === 0 ? 'bg-amber-100 text-amber-800 ring-2 ring-amber-200' : 'bg-slate-100 text-slate-600' }}">
                                        #{{ $index + 1 }}
                                    </span>

                                    <!-- Product Info -->
                                    <div class="min-w-0">
                                        <h3 class="text-xs font-bold text-slate-900 truncate block">
                                            {{ $item->product_name }}
                                        </h3>
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                            @if($item->category)
                                                <span>{{ $item->category }}</span>
                                                <span>&bull;</span>
                                            @endif
                                            @if($item->current_stock !== null)
                                                <span class="{{ $item->current_stock <= 5 ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                                                    {{ $item->current_stock }} in stock
                                                </span>
                                            @else
                                                <span class="text-slate-400">Archived</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Sales Numbers -->
                                <div class="text-right flex-shrink-0">
                                    <span class="text-xs font-black text-slate-900 block">
                                        {{ $item->units_sold }} {{ Str::plural('unit', $item->units_sold) }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 block">
                                        ${{ number_format((float) $item->total_revenue, 2) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">No sales recorded yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                    <span class="text-[11px] text-slate-400">
                        Automatically refreshed upon each order confirmation.
                    </span>
                </div>
            </div>

        </div>

        <!-- 3. RECENT ORDERS TABLE (Requirement 4) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Recent Orders</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Latest customer purchases and current payment/fulfillment statuses.
                    </p>
                </div>
                <a
                    href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors self-start sm:self-auto"
                >
                    <span>View All Orders</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-6">Order #</th>
                            <th class="py-3.5 px-6">Date</th>
                            <th class="py-3.5 px-6">Customer</th>
                            <th class="py-3.5 px-6">Total Amount</th>
                            <th class="py-3.5 px-6">Payment</th>
                            <th class="py-3.5 px-6">Fulfillment</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <!-- Order Number -->
                                <td class="py-4 px-6 font-bold whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->order_number) }}" class="text-brand-600 hover:text-brand-800 hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                </td>

                                <!-- Date -->
                                <td class="py-4 px-6 whitespace-nowrap text-slate-500">
                                    <span class="font-medium text-slate-700 block">{{ $order->created_at->format('M d, Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->created_at->format('h:i A') }} ({{ $order->created_at->diffForHumans() }})</span>
                                </td>

                                <!-- Customer -->
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-900 block truncate max-w-[180px]">{{ $order->customer_name }}</span>
                                    <span class="text-[11px] text-slate-400 block truncate max-w-[180px]">{{ $order->customer_email }}</span>
                                </td>

                                <!-- Total Amount -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="font-black text-slate-900 text-sm">
                                        ${{ number_format((float) $order->total_amount, 2) }}
                                    </span>
                                </td>

                                <!-- Payment Status Pill -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if($order->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Paid</span>
                                        </span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Failed</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Pending (COD)</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Fulfillment Status Pill -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                            'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        ];
                                        $currentStyle = $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $currentStyle }} capitalize">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <a
                                        href="{{ route('admin.orders.show', $order->order_number) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-600 hover:text-white text-slate-700 text-xs font-bold transition-all shadow-sm"
                                        title="View order {{ $order->order_number }}"
                                    >
                                        <span>Details</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    No orders found in database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <!-- Chart.js 4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('dailySalesChart');
            if (!ctx) return;

            const labels = @json($chartLabels);
            const volumeData = @json($chartVolumeData);
            const paidData = @json($chartPaidData);
            const ordersData = @json($chartOrdersData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Sales Volume ($)',
                            data: volumeData,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.08)',
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#4f46e5',
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Paid Revenue ($)',
                            data: paidData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            borderWidth: 2,
                            tension: 0.35,
                            fill: true,
                            pointRadius: 3,
                            pointBackgroundColor: '#10b981',
                            pointHoverRadius: 5,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 'bold' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += '$' + Number(context.parsed.y).toFixed(2);
                                    }
                                    return label;
                                },
                                afterBody: function(tooltipItems) {
                                    const index = tooltipItems[0].dataIndex;
                                    const orders = ordersData[index] || 0;
                                    return 'Orders placed: ' + orders;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                                color: '#64748b'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                                color: '#64748b',
                                callback: function (value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
