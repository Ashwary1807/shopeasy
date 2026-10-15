<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the shopping cart with fresh database price & stock verification.
     */
    public function index(): View
    {
        $sessionCart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0.0;
        $totalQuantity = 0;

        if (!empty($sessionCart)) {
            // Requirement 4: Query fresh active product records from database to verify prices and stock
            $productIds = array_keys($sessionCart);
            $dbProducts = Product::whereIn('id', $productIds)
                ->where('status', 'active')
                ->get()
                ->keyBy('id');

            $updatedCart = [];

            foreach ($sessionCart as $productId => $item) {
                // Skip if product is no longer active or was removed from DB
                if (!$dbProducts->has($productId)) {
                    continue;
                }

                $product = $dbProducts->get($productId);

                // Ensure requested quantity does not exceed available stock
                $qty = (int) ($item['quantity'] ?? 1);
                if ($qty < 1) {
                    $qty = 1;
                }
                if ($qty > $product->stock) {
                    $qty = $product->stock;
                }

                // Skip items whose stock is completely exhausted
                if ($qty <= 0) {
                    continue;
                }

                $itemPrice = (float) $product->price;
                $itemSubtotal = $itemPrice * $qty;
                $subtotal += $itemSubtotal;
                $totalQuantity += $qty;

                $cartItemData = [
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

                $cartItems[$productId] = $cartItemData;
                $updatedCart[$productId] = [
                    'id' => $product->id,
                    'quantity' => $qty,
                ];
            }

            // Keep session cart synchronized with valid active items
            session()->put('cart', $updatedCart);
        }

        // Free shipping on orders $49 or more; flat $5.00 otherwise (if cart has items, evaluated on merchandise subtotal before discount)
        $shipping = 0.0;
        if ($subtotal > 0) {
            $shipping = ($subtotal >= 49.0) ? 0.0 : 5.0;
        }

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

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'discount',
            'appliedCoupon',
            'availableCoupons',
            'total',
            'totalQuantity'
        ));
    }

    /**
     * Add a product to the cart with server-side stock and duplicate validation.
     */
    public function add(Request $request, int $id): RedirectResponse
    {
        // 1. Validate active product existence in database
        $product = Product::where('status', 'active')->findOrFail($id);

        // 2. Validate stock availability
        if ($product->stock <= 0) {
            return back()->with('error', "Sorry, \"{$product->name}\" is currently out of stock.");
        }

        // 3. Server-side quantity validation
        $requestedQuantity = max(1, (int) $request->input('quantity', 1));
        if ($requestedQuantity > $product->stock) {
            return back()->with('error', "Only {$product->stock} units of \"{$product->name}\" are available in stock.");
        }

        // 4. Retrieve current session cart
        $cart = session()->get('cart', []);

        // 5. Duplicate handling: increment quantity instead of adding duplicate row
        $currentQuantity = isset($cart[$id]) ? (int) $cart[$id]['quantity'] : 0;
        $newQuantity = $currentQuantity + $requestedQuantity;

        if ($newQuantity > $product->stock) {
            return back()->with('error', "Cannot add {$requestedQuantity} more. You already have {$currentQuantity} in your cart and only {$product->stock} units are in stock.");
        }

        $cart[$id] = [
            'id' => $product->id,
            'quantity' => $newQuantity,
        ];

        session()->put('cart', $cart);

        return back()->with('success', "Added {$requestedQuantity} × \"{$product->name}\" to your cart!");
    }

    /**
     * Update an item's quantity with stock bounds validation.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::where('status', 'active')->findOrFail($id);

        $quantity = (int) $request->input('quantity', 1);

        if ($quantity < 1) {
            return back()->with('error', 'Quantity must be at least 1.');
        }

        if ($quantity > $product->stock) {
            return back()->with('error', "Only {$product->stock} units of \"{$product->name}\" are available in stock.");
        }

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Item not found in your cart.');
        }

        $cart[$id]['quantity'] = $quantity;
        session()->put('cart', $cart);

        return back()->with('success', "Updated quantity for \"{$product->name}\".");
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(int $id): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from your cart.');
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return back()->with('success', 'Your cart has been cleared.');
    }
}
