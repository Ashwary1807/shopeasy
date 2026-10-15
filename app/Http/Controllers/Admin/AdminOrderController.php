<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    /**
     * Display a paginated list of all orders with status filtering and keyword search.
     */
    public function index(Request $request): View
    {
        // 1. Compute summary counters across all orders
        $metrics = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // 2. Base query with eager-loaded line items
        $query = Order::with('items')->latest('id');

        // 3. Status filter
        $selectedStatus = strtolower(trim((string) $request->query('status', '')));
        if (in_array($selectedStatus, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'], true)) {
            $query->where('status', $selectedStatus);
        } else {
            $selectedStatus = 'all';
        }

        // 4. Keyword search
        $search = trim((string) $request->query('search', ''));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                    ->orWhere('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('customer_email', 'LIKE', "%{$search}%")
                    ->orWhere('customer_phone', 'LIKE', "%{$search}%");
            });
        }

        // 5. Paginate orders and preserve active filter/search query strings (Requirement 2)
        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders', 'metrics', 'selectedStatus', 'search'));
    }

    /**
     * Display comprehensive details for a specific order.
     */
    public function show(string $orderNumber): View
    {
        $order = Order::with('items.product')
            ->where('order_number', trim($orderNumber))
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the fulfillment status of an order.
     */
    public function updateStatus(Request $request, string $orderNumber): RedirectResponse
    {
        // Requirement 3: Validate order status against allowed values
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ], [
            'status.required' => 'Please select an order status.',
            'status.in' => 'Selected order status is invalid.',
        ]);

        $order = Order::where('order_number', trim($orderNumber))->firstOrFail();
        $newStatus = $validated['status'];

        if ($order->status === $newStatus) {
            return back()->with('info', "Order #{$order->order_number} is already in \"{$newStatus}\" status.");
        }

        $previousStatus = $order->status;

        DB::transaction(function () use ($order, $newStatus, $previousStatus) {
            // If transitioning to cancelled, restore coupon usage quota atomically
            if ($newStatus === 'cancelled' && $previousStatus !== 'cancelled') {
                if (!empty($order->coupon_code) && Schema::hasTable('coupons')) {
                    $coupon = Coupon::where('code', $order->coupon_code)->lockForUpdate()->first();
                    if ($coupon && $coupon->used_count > 0) {
                        $coupon->decrement('used_count');
                    }
                }
            }

            $order->status = $newStatus;
            $order->save();
        });

        // Requirement 3: Note for COD: do NOT automatically mark payment as paid on delivery.
        $message = "Order #{$order->order_number} status changed from \"{$previousStatus}\" to \"{$newStatus}\".";
        if ($newStatus === 'delivered' && $order->payment_method === 'cod' && $order->payment_status === 'pending') {
            $message .= ' (Note: Payment status remains "Pending". Please update payment to "Paid" once cash collection is verified).';
        }

        return back()->with('success', $message);
    }

    /**
     * Update the payment status of an order.
     */
    public function updatePayment(Request $request, string $orderNumber): RedirectResponse
    {
        // Requirement 3: Validate payment status against allowed values
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ], [
            'payment_status.required' => 'Please select a payment status.',
            'payment_status.in' => 'Selected payment status is invalid.',
        ]);

        $order = Order::where('order_number', trim($orderNumber))->firstOrFail();
        $newPaymentStatus = $validated['payment_status'];

        if ($order->payment_status === $newPaymentStatus) {
            return back()->with('info', "Payment status for Order #{$order->order_number} is already \"{$newPaymentStatus}\".");
        }

        $previousPaymentStatus = $order->payment_status;
        $order->payment_status = $newPaymentStatus;
        $order->save();

        return back()->with('success', "Order #{$order->order_number} payment status updated from \"{$previousPaymentStatus}\" to \"{$newPaymentStatus}\".");
    }
}
