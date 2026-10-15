<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display customer order history or recent session orders and lookup form.
     */
    public function index(): View
    {
        $recentOrderNumbers = session()->get('recent_orders', []);
        if (!is_array($recentOrderNumbers)) {
            $recentOrderNumbers = [];
        }

        if (auth()->check()) {
            // Automatically display all orders belonging to authenticated customer
            $orders = Order::with('items')
                ->where('user_id', auth()->id())
                ->latest('id')
                ->paginate(5);
        } else {
            // Safely paginate session-associated recent orders for guests
            if (!empty($recentOrderNumbers)) {
                $orders = Order::with('items')
                    ->whereIn('order_number', $recentOrderNumbers)
                    ->latest('id')
                    ->paginate(5);
            } else {
                $orders = Order::whereRaw('1 = 0')->paginate(5);
            }
        }

        return view('orders.index', compact('orders', 'recentOrderNumbers'));
    }

    /**
     * Securely look up an order requiring both order_number and matching customer_email.
     */
    public function lookup(Request $request): RedirectResponse
    {
        $request->validate([
            'order_number' => 'required|string|max:50',
            'customer_email' => 'required|email|max:150',
        ], [
            'order_number.required' => 'Please enter your Order Number (e.g. ORD-...).',
            'customer_email.required' => 'Please enter the email address used when placing the order.',
            'customer_email.email' => 'Please enter a valid email address.',
        ]);

        // 1. Normalize and trim input (Requirement 1)
        $orderNumber = trim($request->input('order_number'));
        $email = strtolower(trim($request->input('customer_email')));

        // 2. Case-insensitive database matching (Requirement 1 & 4)
        $order = Order::where('order_number', $orderNumber)
            ->whereRaw('LOWER(customer_email) = ?', [$email])
            ->first();

        if (!$order) {
            return back()
                ->withInput()
                ->with('error', 'No order found matching the provided Order Number and Email address. Please verify your details.');
        }

        // Privacy check: If order belongs to a registered customer and visitor is not logged in
        if ($order->user_id && !auth()->check()) {
            session()->put('url.intended', route('orders.show', $order->order_number));
            return redirect()->route('login')
                ->with('info', 'This order is linked to a customer account. Please log in to view its details.');
        }

        // Privacy check: If order belongs to another registered customer
        if ($order->user_id && auth()->check() && $order->user_id !== auth()->id()) {
            return back()
                ->withInput()
                ->with('error', 'You are not authorized to view this order.');
        }

        // 3. Store in session recent_orders list to allow convenient access without duplicates
        $recentOrders = session()->get('recent_orders', []);
        if (!is_array($recentOrders)) {
            $recentOrders = [];
        }
        if (!in_array($order->order_number, $recentOrders, true)) {
            $recentOrders[] = $order->order_number;
            session()->put('recent_orders', $recentOrders);
        }

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', "Order #{$order->order_number} found successfully.");
    }

    /**
     * Display detailed order view with access control and strict customer privacy.
     */
    public function show(Request $request, string $orderNumber): View|RedirectResponse
    {
        $orderNumber = trim($orderNumber);

        // Fetch order from database
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Order not found.');
        }

        // Enforce strict customer privacy
        if ($order->user_id) {
            if (auth()->check()) {
                // If order is customer-owned, only the owner can view it
                if ($order->user_id !== auth()->id()) {
                    return redirect()->route('orders.index')
                        ->with('error', 'You are not authorized to view this order.');
                }
            } else {
                // If a guest tries to view an order linked to a registered account, require login
                session()->put('url.intended', route('orders.show', $orderNumber));
                return redirect()->route('login')
                    ->with('info', 'This order is linked to a customer account. Please log in to view its details.');
            }
        } else {
            // Legacy guest orders (user_id is null): check session or matching email
            $recentOrders = session()->get('recent_orders', []);
            if (!is_array($recentOrders)) {
                $recentOrders = [];
            }
            $isSessionAuthorized = in_array($orderNumber, $recentOrders, true);

            $suppliedEmail = strtolower(trim((string) $request->query('email', '')));
            $isEmailAuthorized = !empty($suppliedEmail) && strtolower(trim($order->customer_email)) === $suppliedEmail;
            $isUserEmailMatched = auth()->check() && strtolower(trim($order->customer_email)) === strtolower(trim(auth()->user()->email));

            if (!$isSessionAuthorized && !$isEmailAuthorized && !$isUserEmailMatched) {
                return redirect()->route('orders.index')
                    ->with('error', 'Please look up your order with your Order Number and matching Email address to view its details.');
            }

            // Cache into session recent_orders if authorized
            if (($isEmailAuthorized || $isUserEmailMatched) && !$isSessionAuthorized) {
                $recentOrders[] = $order->order_number;
                session()->put('recent_orders', $recentOrders);
            }
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel a pending order and replenish catalog product stock within an atomic transaction.
     */
    public function cancel(Request $request, string $orderNumber): RedirectResponse
    {
        $orderNumber = trim($orderNumber);
        $user = $request->user();

        // 1. Enforce ownership: must belong to the authenticated customer
        $order = $user->orders()->where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Order not found or you are not authorized to cancel this order.');
        }

        // 2. Reject cancellation if not currently pending
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order->order_number)
                ->with('error', "Order #{$order->order_number} cannot be cancelled because its current status is \"{$order->status}\". Only pending orders can be cancelled.");
        }

        try {
            DB::transaction(function () use ($user, $orderNumber) {
                // 3. Lock the order row with pessimistic locking
                $lockedOrder = $user->orders()
                    ->where('order_number', $orderNumber)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 4. Recheck status under lock to prevent race conditions and duplicate restoration
                if ($lockedOrder->status !== 'pending') {
                    throw new \RuntimeException("Order #{$lockedOrder->order_number} cannot be cancelled because its status is already \"{$lockedOrder->status}\".");
                }

                // 5. Aggregate quantities by product ID from order items
                $items = $lockedOrder->items()->get();
                $productQuantities = [];
                foreach ($items as $item) {
                    if ($item->product_id) {
                        $productQuantities[$item->product_id] = ($productQuantities[$item->product_id] ?? 0) + $item->quantity;
                    }
                }

                // 6. Lock associated products in ascending product ID order to reduce deadlock risk
                if (!empty($productQuantities)) {
                    $sortedProductIds = array_keys($productQuantities);
                    sort($sortedProductIds, SORT_NUMERIC);

                    $products = Product::whereIn('id', $sortedProductIds)
                        ->orderBy('id', 'asc')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    // 7. Restore stock recorded in order items
                    foreach ($productQuantities as $productId => $qty) {
                        if ($products->has($productId)) {
                            $products->get($productId)->increment('stock', $qty);
                        }
                    }
                }

                // 8. Restore coupon usage quota atomically and idempotently
                if (!empty($lockedOrder->coupon_code) && Schema::hasTable('coupons')) {
                    $coupon = Coupon::where('code', $lockedOrder->coupon_code)->lockForUpdate()->first();
                    if ($coupon && $coupon->used_count > 0) {
                        $coupon->decrement('used_count');
                    }
                }

                // 9. Update order status to cancelled; keep payment status unchanged
                $lockedOrder->status = 'cancelled';
                $lockedOrder->save();
            });

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', "Order #{$order->order_number} has been cancelled successfully and product inventory has been restored.");

        } catch (\Throwable $e) {
            return redirect()->route('orders.show', $order->order_number)
                ->with('error', $e->getMessage());
        }
    }
}
