<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page with server-verified cart details.
     */
    public function index(): View|RedirectResponse
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your shopping cart is empty. Please add items before proceeding to checkout.');
        }

        // Fetch fresh product records from MySQL database
        $productIds = array_keys($sessionCart);
        $dbProducts = Product::whereIn('id', $productIds)
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $subtotal = 0.0;
        $totalQuantity = 0;
        $updatedCart = [];

        foreach ($sessionCart as $productId => $item) {
            if (!$dbProducts->has($productId)) {
                continue;
            }

            $product = $dbProducts->get($productId);

            $qty = (int) ($item['quantity'] ?? 1);
            if ($qty < 1) {
                $qty = 1;
            }
            if ($qty > $product->stock) {
                $qty = $product->stock;
            }

            if ($qty <= 0) {
                continue;
            }

            $itemPrice = (float) $product->price;
            $itemSubtotal = $itemPrice * $qty;
            $subtotal += $itemSubtotal;
            $totalQuantity += $qty;

            $cartItems[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $itemPrice,
                'original_price' => $product->original_price ? (float) $product->original_price : null,
                'image' => $product->image,
                'category' => $product->category,
                'stock' => $product->stock,
                'quantity' => $qty,
                'subtotal' => $itemSubtotal,
            ];

            $updatedCart[$productId] = [
                'id' => $product->id,
                'quantity' => $qty,
            ];
        }

        // Keep session cart synchronized with valid items
        session()->put('cart', $updatedCart);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'None of the items in your cart are currently available in stock.');
        }

        // Free shipping on orders $49 or more; flat $5.00 otherwise (evaluated on merchandise subtotal before discount)
        $shipping = ($subtotal >= 49.0) ? 0.0 : 5.0;

        $discount = 0.0;
        $appliedCoupon = null;

        if (session()->has('applied_coupon') && Schema::hasTable('coupons')) {
            $couponData = session()->get('applied_coupon');
            $couponCode = is_array($couponData) ? ($couponData['code'] ?? null) : $couponData;

            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                $errorMsg = null;
                if ($coupon && $coupon->isValidForSubtotal($subtotal, $errorMsg, $cartItems)) {
                    $discount = $coupon->calculateDiscount($subtotal, $cartItems);
                    $appliedCoupon = $coupon;
                } else {
                    // Invalidate coupon if no longer eligible (e.g. cart subtotal dropped)
                    session()->forget('applied_coupon');
                    session()->flash('warning', "The coupon \"{$couponCode}\" was removed because your cart no longer meets the eligibility criteria.");
                }
            }
        }

        $total = max(0.0, $subtotal - $discount) + $shipping;

        $availableCoupons = Schema::hasTable('coupons')
            ? Coupon::where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                })
                ->orderBy('id', 'asc')
                ->get()
            : collect();

        $user = auth()->user();
        $savedAddresses = $user ? $user->addresses()->orderByDesc('is_default')->latest('id')->get() : collect();
        $defaultAddress = $savedAddresses->firstWhere('is_default', true) ?? $savedAddresses->first();
        $defaultAddressId = $defaultAddress ? $defaultAddress->id : 'new';
        $canSaveMoreAddresses = $savedAddresses->count() < 10;

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'discount',
            'appliedCoupon',
            'availableCoupons',
            'total',
            'totalQuantity',
            'savedAddresses',
            'defaultAddressId',
            'canSaveMoreAddresses'
        ));
    }

    /**
     * Process checkout form, validate customer input, verify stock, and save order within a transaction.
     */
    public function process(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Common inputs validation
        $request->validate([
            'customer_email' => 'required|email|max:150',
            'notes' => 'nullable|string|max:500',
            'selected_address_id' => 'required',
        ], [
            'customer_email.required' => 'Please enter your email address for order updates.',
            'customer_email.email' => 'Please provide a valid email format (e.g. name@example.com).',
            'selected_address_id.required' => 'Please select a delivery address or enter a new one.',
        ]);

        $selectedAddressId = $request->input('selected_address_id');
        $addressToSave = null;
        $recipientName = null;
        $recipientPhone = null;
        $deliveryAddressSnapshot = null;

        if ($selectedAddressId === 'new' || $user->addresses()->count() === 0) {
            // Validate structured new address fields
            $validatedAddress = $request->validate([
                'new_label' => 'required|string|in:Home,Work,Other',
                'new_recipient_name' => 'required|string|min:2|max:100',
                'new_phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:7|max:25',
                'new_address_line1' => 'required|string|min:5|max:255',
                'new_address_line2' => 'nullable|string|max:255',
                'new_city' => 'required|string|min:2|max:100',
                'new_state' => 'required|string|min:2|max:100',
                'new_postal_code' => 'required|string|min:3|max:20',
                'new_country' => 'required|string|min:2|max:100',
                'save_address' => 'nullable|boolean',
            ], [
                'new_label.required' => 'Please select an address label (Home, Work, Other).',
                'new_label.in' => 'Address label must be Home, Work, or Other.',
                'new_recipient_name.required' => 'Please enter the recipient full name.',
                'new_recipient_name.min' => 'Recipient name must be at least 2 characters.',
                'new_phone.required' => 'Please enter a contact phone number.',
                'new_phone.regex' => 'Please enter a valid phone number (digits, spaces, hyphens, plus sign).',
                'new_address_line1.required' => 'Please provide the street address (Line 1).',
                'new_address_line1.min' => 'Street address must be at least 5 characters.',
                'new_city.required' => 'Please enter the city.',
                'new_state.required' => 'Please enter the state or region.',
                'new_postal_code.required' => 'Please enter the postal / ZIP code.',
                'new_country.required' => 'Please enter the country.',
            ]);

            $recipientName = trim($validatedAddress['new_recipient_name']);
            $recipientPhone = trim($validatedAddress['new_phone']);

            $parts = [
                trim($validatedAddress['new_address_line1']),
                !empty($validatedAddress['new_address_line2']) ? trim($validatedAddress['new_address_line2']) : null,
                trim("{$validatedAddress['new_city']}, {$validatedAddress['new_state']} {$validatedAddress['new_postal_code']}"),
                trim($validatedAddress['new_country']),
            ];
            $deliveryAddressSnapshot = implode(', ', array_filter(array_map('trim', $parts)));

            if ($request->boolean('save_address') && $user->addresses()->count() < 10) {
                $addressToSave = $validatedAddress;
            }
        } else {
            // Selected a saved address
            if (!is_string($selectedAddressId) || !ctype_digit($selectedAddressId)) {
                return back()->withInput()->withErrors([
                    'selected_address_id' => 'Please select a valid delivery address.',
                ]);
            }

            // Strictly scoped to the authenticated customer (prevents IDOR)
            $savedAddress = $user->addresses()->find((int) $selectedAddressId);

            if (!$savedAddress) {
                return back()->withInput()->withErrors([
                    'selected_address_id' => 'The selected delivery address is invalid or does not belong to your account.',
                ]);
            }

            $recipientName = $savedAddress->recipient_name;
            $recipientPhone = $savedAddress->phone;
            $deliveryAddressSnapshot = $savedAddress->formatted_address;
        }

        $customerEmail = trim($request->input('customer_email'));
        $notes = $request->filled('notes') ? trim($request->input('notes')) : null;

        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your shopping cart is empty.');
        }

        try {
            // 2. Execute address saving and order creation inside a database transaction with stock locking
            $order = DB::transaction(function () use (
                $user,
                $sessionCart,
                $recipientName,
                $recipientPhone,
                $customerEmail,
                $deliveryAddressSnapshot,
                $notes,
                $addressToSave
            ) {
                // If customer requested to save new address and is under 10 limit, persist inside transaction
                if ($addressToSave) {
                    $isFirst = $user->addresses()->count() === 0;
                    $user->addresses()->create([
                        'label' => $addressToSave['new_label'],
                        'recipient_name' => trim($addressToSave['new_recipient_name']),
                        'phone' => trim($addressToSave['new_phone']),
                        'address_line1' => trim($addressToSave['new_address_line1']),
                        'address_line2' => !empty($addressToSave['new_address_line2']) ? trim($addressToSave['new_address_line2']) : null,
                        'city' => trim($addressToSave['new_city']),
                        'state' => trim($addressToSave['new_state']),
                        'postal_code' => trim($addressToSave['new_postal_code']),
                        'country' => trim($addressToSave['new_country']),
                        'is_default' => $isFirst,
                    ]);
                }

                $productIds = array_keys($sessionCart);

                // Lock product records to prevent race conditions during concurrent orders
                $dbProducts = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $subtotal = 0.0;
                $orderItemsData = [];

                foreach ($sessionCart as $productId => $cartItem) {
                    $product = $dbProducts->get($productId);

                    if (!$product || $product->status !== 'active') {
                        throw new \RuntimeException("The item \"{$cartItem['id']}\" is no longer available. Please check your cart.");
                    }

                    $requestedQty = (int) ($cartItem['quantity'] ?? 1);
                    if ($requestedQty < 1) {
                        $requestedQty = 1;
                    }

                    // Stock check before order creation
                    if ($requestedQty > $product->stock) {
                        throw new \RuntimeException("Insufficient stock for \"{$product->name}\". Only {$product->stock} unit(s) remaining, but {$requestedQty} requested.");
                    }

                    $itemPrice = (float) $product->price;
                    $lineSubtotal = $itemPrice * $requestedQty;
                    $subtotal += $lineSubtotal;

                    $orderItemsData[] = [
                        'product' => $product,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $itemPrice,
                        'quantity' => $requestedQty,
                        'item_subtotal' => $lineSubtotal,
                    ];
                }

                if (empty($orderItemsData)) {
                    throw new \RuntimeException('No valid items found in your cart.');
                }

                // Shipping calculation: Free over $49, otherwise $5.00 (evaluated on merchandise subtotal)
                $shipping = ($subtotal >= 49.0) ? 0.0 : 5.0;

                $discount = 0.0;
                $appliedCouponCode = null;

                if (session()->has('applied_coupon') && Schema::hasTable('coupons')) {
                    $couponData = session()->get('applied_coupon');
                    $code = is_array($couponData) ? ($couponData['code'] ?? null) : $couponData;

                    if ($code) {
                        // Pessimistic locking to serialize coupon usage and enforce redemption limits atomically
                        $coupon = Coupon::where('code', $code)->lockForUpdate()->first();
                        $errorMsg = null;

                        if (!$coupon || !$coupon->isValidForSubtotal($subtotal, $errorMsg, $orderItemsData)) {
                            throw new \RuntimeException($errorMsg ?? "The coupon \"{$code}\" is no longer valid or has reached its redemption limit.");
                        }

                        $discount = $coupon->calculateDiscount($subtotal, $orderItemsData);
                        $appliedCouponCode = $coupon->code;

                        // Increment usage count atomically within transaction
                        $coupon->increment('used_count');
                    }
                }

                $total = max(0.0, $subtotal - $discount) + $shipping;

                // Generate unique order number (format: ORD-YYMMDD-XXXXXX)
                do {
                    $orderNumber = 'ORD-' . strtoupper(date('ymd')) . '-' . strtoupper(Str::random(6));
                } while (Order::where('order_number', $orderNumber)->exists());

                $orderData = [
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'customer_name' => $recipientName,
                    'customer_email' => $customerEmail,
                    'customer_phone' => $recipientPhone,
                    'delivery_address' => $deliveryAddressSnapshot,
                    'notes' => $notes,
                    'subtotal' => $subtotal,
                    'shipping_charges' => $shipping,
                    'total_amount' => $total,
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'status' => 'pending',
                ];

                if (Schema::hasColumn('orders', 'coupon_code')) {
                    $orderData['coupon_code'] = $appliedCouponCode;
                    $orderData['discount_amount'] = $discount;
                }

                // Create Order record with pending initial status and COD payment method
                $order = Order::create($orderData);

                // Create OrderItem records and decrement product stock
                foreach ($orderItemsData as $itemData) {
                    $order->items()->create([
                        'product_id' => $itemData['product_id'],
                        'product_name' => $itemData['product_name'],
                        'unit_price' => $itemData['unit_price'],
                        'quantity' => $itemData['quantity'],
                        'item_subtotal' => $itemData['item_subtotal'],
                    ]);

                    // Deduct product stock within transaction
                    /** @var Product $product */
                    $product = $itemData['product'];
                    $product->decrement('stock', $itemData['quantity']);
                }

                return $order;
            });

            // 3. Clear session cart and applied coupon strictly AFTER successful order creation
            session()->forget(['cart', 'applied_coupon']);

            // 4. Safely track order number in session recent_orders array (Requirement 3)
            $recentOrders = session()->get('recent_orders', []);
            if (!is_array($recentOrders)) {
                $recentOrders = [];
            }
            if (!in_array($order->order_number, $recentOrders, true)) {
                $recentOrders[] = $order->order_number;
                session()->put('recent_orders', $recentOrders);
            }

            // 5. Trigger admin notification for new order strictly AFTER transaction commit
            try {
                AdminNotification::createForNewOrder($order);
            } catch (\Throwable $notifEx) {
                Log::warning("Checkout admin notification dispatch encountered an error for order {$order->order_number}: " . $notifEx->getMessage(), [
                    'order_id' => $order->id,
                    'exception' => $notifEx,
                ]);
            }

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Your order has been placed successfully!');

        } catch (\Throwable $e) {
            // Transaction is rolled back automatically; session cart is preserved
            return redirect()->route('cart.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display order confirmation page with unique order number and item details.
     */
    public function success(string $orderNumber): View
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
