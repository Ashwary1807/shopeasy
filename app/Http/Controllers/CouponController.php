<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CouponController extends Controller
{
    /**
     * Validate and apply a coupon to the active checkout session.
     */
    public function apply(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ], [
            'coupon_code.required' => 'Please enter a coupon code.',
            'coupon_code.string' => 'Please enter a valid coupon code.',
            'coupon_code.max' => 'Coupon code cannot exceed 50 characters.',
        ]);

        if (!Schema::hasTable('coupons')) {
            return back()->with('error', 'The coupon service is currently unavailable.');
        }

        $code = strtoupper(trim($request->input('coupon_code')));

        // Fetch session cart and re-verify authoritative prices/stock from database
        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) {
            return back()->with('error', 'Your shopping cart is empty.');
        }

        $productIds = array_keys($sessionCart);
        $dbProducts = Product::whereIn('id', $productIds)
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        $subtotal = 0.0;
        $cartItems = [];

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
            $lineSubtotal = $itemPrice * $qty;
            $subtotal += $lineSubtotal;

            $cartItems[] = [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'price' => $itemPrice,
                'quantity' => $qty,
                'subtotal' => $lineSubtotal,
            ];
        }

        if (empty($cartItems) || $subtotal <= 0.0) {
            return back()->with('error', 'Your shopping cart contains no available items.');
        }

        // Query coupon record from database
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->withInput()->with('error', "Invalid coupon code \"{$code}\". Please check the spelling and try again.");
        }

        // Validate eligibility against database constraints
        $errorMessage = null;
        if (!$coupon->isValidForSubtotal($subtotal, $errorMessage, $cartItems)) {
            return back()->withInput()->with('error', $errorMessage);
        }

        // Calculate verified discount amount on the server
        $discount = $coupon->calculateDiscount($subtotal, $cartItems);

        if ($discount <= 0.0) {
            return back()->withInput()->with('error', "The coupon \"{$coupon->code}\" does not apply to any eligible items in your cart.");
        }

        // Single coupon policy: Replace previously applied coupon
        session()->put('applied_coupon', [
            'code' => $coupon->code,
        ]);

        return back()->with('success', "Coupon \"{$coupon->code}\" applied successfully! You saved $" . number_format($discount, 2) . " on your order.");
    }

    /**
     * Remove the currently applied coupon from the checkout session.
     */
    public function remove(Request $request): RedirectResponse
    {
        session()->forget('applied_coupon');

        return back()->with('success', 'Coupon removed successfully.');
    }
}
