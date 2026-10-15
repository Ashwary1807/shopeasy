<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Analytics Dashboard.
     */
    public function index(Request $request): View
    {
        // 1. Order Status Counts
        $statusCounts = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalOrders = Order::count();
        $pendingOrders = (int) ($statusCounts['pending'] ?? 0);
        $processingOrders = (int) ($statusCounts['processing'] ?? 0);
        $shippedOrders = (int) ($statusCounts['shipped'] ?? 0);
        $deliveredOrders = (int) ($statusCounts['delivered'] ?? 0);
        $cancelledOrders = (int) ($statusCounts['cancelled'] ?? 0);

        // 2. Revenue Calculations (Safeguards 3 & 4)
        // Realized Revenue: payment_status = 'paid' and status != 'cancelled'
        $paidRevenue = (float) Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Pending COD Collection: payment_status = 'pending' and status != 'cancelled'
        $pendingCollection = (float) Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'pending')
            ->sum('total_amount');

        // Total non-cancelled sales volume / gross order value (Safeguard 4)
        $totalSalesVolume = (float) Order::where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Cancelled orders total amount (for visibility)
        $cancelledOrderValue = (float) Order::where('status', 'cancelled')
            ->sum('total_amount');

        // 3. Customer Counts (Safeguard 6)
        // Registered customer accounts
        $registeredCustomersCount = User::count();
        // Unique customer emails that have placed orders
        $uniqueOrderCustomersCount = Order::distinct('customer_email')->count('customer_email');

        // 4. Sales Chart Aggregations with Date Range Filter (Requirement 2)
        $range = strtolower(trim((string) $request->query('range', '7days')));
        $daysCount = match ($range) {
            '14days' => 14,
            '30days' => 30,
            default => 7,
        };

        $startDate = Carbon::today()->subDays($daysCount - 1)->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        // Consolidated daily sales query excluding cancelled orders
        $dailyAggregates = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as order_date'),
                DB::raw('SUM(total_amount) as total_volume'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_amount ELSE 0 END) as paid_revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('order_date');

        $chartLabels = [];
        $chartVolumeData = [];
        $chartPaidData = [];
        $chartOrdersData = [];

        $currentDay = $startDate->copy();
        while ($currentDay->lte($endDate)) {
            $dateKey = $currentDay->format('Y-m-d');
            $chartLabels[] = $currentDay->format('M d');

            $agg = $dailyAggregates->get($dateKey);
            $chartVolumeData[] = $agg ? round((float) $agg->total_volume, 2) : 0.0;
            $chartPaidData[] = $agg ? round((float) $agg->paid_revenue, 2) : 0.0;
            $chartOrdersData[] = $agg ? (int) $agg->order_count : 0;

            $currentDay->addDay();
        }

        // Summary totals within the selected chart window
        $rangeTotalVolume = array_sum($chartVolumeData);
        $rangePaidRevenue = array_sum($chartPaidData);
        $rangeTotalOrders = array_sum($chartOrdersData);

        // 5. Top-Selling Products (Safeguard 5)
        // Exclude cancelled orders and aggregate order_items accurately
        $topSellingAggregates = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.item_subtotal) as total_revenue')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();

        $productIds = $topSellingAggregates->pluck('product_id')->filter()->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $topSellingProducts = $topSellingAggregates->map(function ($item) use ($products) {
            $product = $item->product_id ? $products->get($item->product_id) : null;
            $item->product = $product;
            $item->current_stock = $product?->stock;
            $item->product_image = $product?->image;
            $item->category = $product?->category;
            $item->is_active = $product?->status === 'active';
            return $item;
        });

        // 6. Recent Orders Table (Requirement 4)
        $recentOrders = Order::latest('id')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'paidRevenue',
            'pendingCollection',
            'totalSalesVolume',
            'cancelledOrderValue',
            'registeredCustomersCount',
            'uniqueOrderCustomersCount',
            'range',
            'daysCount',
            'chartLabels',
            'chartVolumeData',
            'chartPaidData',
            'chartOrdersData',
            'rangeTotalVolume',
            'rangePaidRevenue',
            'rangeTotalOrders',
            'topSellingProducts',
            'recentOrders'
        ));
    }
}
